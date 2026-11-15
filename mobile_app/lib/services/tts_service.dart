import 'package:flutter_tts/flutter_tts.dart';

enum TtsState { playing, stopped, paused }

class TtsService {
  static final TtsService _instance = TtsService._internal();
  factory TtsService() => _instance;
  TtsService._internal();

  final FlutterTts _flutterTts = FlutterTts();
  TtsState _state = TtsState.stopped;
  String? _currentArticleSlug;

  TtsState get state => _state;
  String? get currentArticleSlug => _currentArticleSlug;

  Function(TtsState)? onStateChanged;

  Future<void> init() async {
    await _flutterTts.setSpeechRate(0.5);
    await _flutterTts.setVolume(1.0);
    await _flutterTts.setPitch(1.0);

    _flutterTts.setStartHandler(() {
      _state = TtsState.playing;
      onStateChanged?.call(_state);
    });

    _flutterTts.setCompletionHandler(() {
      _state = TtsState.stopped;
      _currentArticleSlug = null;
      onStateChanged?.call(_state);
    });

    _flutterTts.setCancelHandler(() {
      _state = TtsState.stopped;
      _currentArticleSlug = null;
      onStateChanged?.call(_state);
    });

    _flutterTts.setPauseHandler(() {
      _state = TtsState.paused;
      onStateChanged?.call(_state);
    });

    _flutterTts.setContinueHandler(() {
      _state = TtsState.playing;
      onStateChanged?.call(_state);
    });
  }

  Future<void> speak(String text, String slug, {String language = 'en-US'}) async {
    if (_state == TtsState.playing && _currentArticleSlug == slug) {
      await pause();
      return;
    }

    if (_state == TtsState.paused && _currentArticleSlug == slug) {
      await _flutterTts.speak(text);
      return;
    }

    await stop();
    _currentArticleSlug = slug;
    await _flutterTts.setLanguage(language);
    await _flutterTts.speak(text);
  }

  Future<void> pause() async {
    await _flutterTts.pause();
    _state = TtsState.paused;
    onStateChanged?.call(_state);
  }

  Future<void> stop() async {
    await _flutterTts.stop();
    _state = TtsState.stopped;
    _currentArticleSlug = null;
    onStateChanged?.call(_state);
  }
}


import 'package:flutter/material.dart';
import '../services/tts_service.dart';

class AudioPlayerBar extends StatefulWidget {
  final String title;
  final String text;
  final String slug;

  const AudioPlayerBar({
    super.key,
    required this.title,
    required this.text,
    required this.slug,
  });

  @override
  State<AudioPlayerBar> createState() => _AudioPlayerBarState();
}

class _AudioPlayerBarState extends State<AudioPlayerBar> {
  final TtsService _tts = TtsService();
  TtsState _state = TtsState.stopped;

  @override
  void initState() {
    super.initState();
    _state = _tts.state;
    _tts.onStateChanged = (state) {
      if (mounted) setState(() => _state = state);
    };
  }

  @override
  Widget build(BuildContext context) {
    if (_state == TtsState.stopped && _tts.currentArticleSlug != widget.slug) {
      return const SizedBox.shrink();
    }

    final isPlaying = _state == TtsState.playing;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: const Color(0xFF0F172A),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.3),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          IconButton(
            icon: Icon(
              isPlaying ? Icons.pause_circle_filled : Icons.play_circle_filled,
              color: const Color(0xFFE11D48),
              size: 36,
            ),
            onPressed: () {
              _tts.speak(widget.text, widget.slug);
            },
          ),
          const SizedBox(width: 8),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  children: [
                    const Icon(Icons.volume_up, color: Color(0xFFF59E0B), size: 14),
                    const SizedBox(width: 4),
                    Text(
                      isPlaying ? 'PLAYING AUDIO NEWS' : 'AUDIO PAUSED',
                      style: const TextStyle(
                        color: Color(0xFFF59E0B),
                        fontSize: 10,
                        fontWeight: FontWeight.w900,
                        letterSpacing: 0.5,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 2),
                Text(
                  widget.title,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 13,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ],
            ),
          ),
          IconButton(
            icon: const Icon(Icons.close, color: Colors.white70, size: 20),
            onPressed: () => _tts.stop(),
          ),
        ],
      ),
    );
  }
}

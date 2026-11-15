import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../config/app_config.dart';
import '../config/app_constants.dart';
import '../models/app_config_model.dart';
import '../services/api_service.dart';

class AppProvider with ChangeNotifier {
  late ApiService apiService;
  AppConfigModel? config;

  String _currentLocale = AppConfig.defaultLocale;
  bool _isDarkMode = false;
  String? _selectedCitySlug;
  String? _selectedCityName;
  bool _isLoading = true;

  String get currentLocale => _currentLocale;
  bool get isDarkMode => _isDarkMode;
  bool get isRtl => _currentLocale == 'ar' || _currentLocale == 'ur';
  String? get selectedCitySlug => _selectedCitySlug;
  String? get selectedCityName => _selectedCityName;
  bool get isLoading => _isLoading;

  AppProvider() {
    apiService = ApiService(currentLocale: _currentLocale);
    _initPreferences();
  }

  Future<void> _initPreferences() async {
    final prefs = await SharedPreferences.getInstance();
    _currentLocale = prefs.getString(AppConstants.keyUserLocale) ?? AppConfig.defaultLocale;
    _isDarkMode = prefs.getBool(AppConstants.keyIsDarkMode) ?? false;
    _selectedCitySlug = prefs.getString(AppConstants.keySelectedCity);
    _selectedCityName = prefs.getString(AppConstants.keySelectedCityName);

    apiService.currentLocale = _currentLocale;

    try {
      config = await apiService.fetchConfig();
    } catch (e) {
      debugPrint('Error loading app config: $e');
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<void> setLocale(String locale) async {
    if (_currentLocale == locale) return;
    _currentLocale = locale;
    apiService.currentLocale = locale;

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConstants.keyUserLocale, locale);

    notifyListeners();
  }

  Future<void> toggleDarkMode() async {
    _isDarkMode = !_isDarkMode;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool(AppConstants.keyIsDarkMode, _isDarkMode);
    notifyListeners();
  }

  Future<void> setSelectedCity(String? slug, String? name) async {
    _selectedCitySlug = slug;
    _selectedCityName = name;

    final prefs = await SharedPreferences.getInstance();
    if (slug == null) {
      await prefs.remove(AppConstants.keySelectedCity);
      await prefs.remove(AppConstants.keySelectedCityName);
    } else {
      await prefs.setString(AppConstants.keySelectedCity, slug);
      if (name != null) await prefs.setString(AppConstants.keySelectedCityName, name);
    }

    notifyListeners();
  }
}

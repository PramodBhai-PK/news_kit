import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';

class AuthProvider with ChangeNotifier {
  ApiService _apiService;
  ApiService get apiService => _apiService;
  UserModel? _currentUser;
  bool _isLoggedIn = false;
  bool _isLoading = false;

  UserModel? get currentUser => _currentUser;
  bool get isLoggedIn => _isLoggedIn;
  bool get isLoading => _isLoading;

  AuthProvider({required ApiService apiService}) : _apiService = apiService {
    _loadUserFromStorage();
  }

  void updateApiService(ApiService newService) {
    _apiService = newService;
  }

  Future<void> _loadUserFromStorage() async {
    _currentUser = await AuthService.getUser();
    _isLoggedIn = await AuthService.isLoggedIn();
    notifyListeners();
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    final result = await apiService.login(email, password);
    if (result['success'] == true) {
      _currentUser = result['user'];
      _isLoggedIn = true;
    }

    _isLoading = false;
    notifyListeners();
    return result;
  }

  Future<Map<String, dynamic>> register(String name, String email, String password, {String? phone}) async {
    _isLoading = true;
    notifyListeners();

    final result = await apiService.register(name, email, password, phone: phone);
    if (result['success'] == true) {
      _currentUser = result['user'];
      _isLoggedIn = true;
    }

    _isLoading = false;
    notifyListeners();
    return result;
  }

  Future<void> logout() async {
    await AuthService.logout();
    _currentUser = null;
    _isLoggedIn = false;
    notifyListeners();
  }
}


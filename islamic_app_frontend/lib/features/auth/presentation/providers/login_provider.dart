// lib/features/auth/presentation/providers/login_provider.dart

import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class LoginProvider extends ChangeNotifier {
  // ✅ Controllers & UI state — مُضمنة كما طلبت
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  bool isPasswordVisible = false;

  // State
  bool _isLoading = false;
  String? _token;
  String? _errorMessage;

  // Getters
  bool get isLoading => _isLoading;
  String? get token => _token;
  String? get errorMessage => _errorMessage;

  // API Config — ✅ مسافات زائدة مُزالة
  static const String _baseUrl = 'https://islamic-application.onrender.com';
  static const String _loginEndpoint = '/api/auth/login';

  // Actions
  void togglePasswordVisibility() {
    isPasswordVisible = !isPasswordVisible;
    notifyListeners();
  }

  void setLoading(bool v) {
    _isLoading = v;
    notifyListeners();
  }

  void clearError() {
    _errorMessage = null;
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _errorMessage = null;
    setLoading(true);

    try {
      if (email.trim().isEmpty) {
        _errorMessage = 'البريد الإلكتروني مطلوب';
        return false;
      }
      if (password.isEmpty) {
        _errorMessage = 'كلمة المرور مطلوبة';
        return false;
      }
      if (!RegExp(r'^[^@]+@[^@]+\.[^@]+').hasMatch(email)) {
        _errorMessage = 'بريد إلكتروني غير صالح';
        return false;
      }

      final url = Uri.parse('$_baseUrl$_loginEndpoint');
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email.trim(), 'password': password}),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as Map<String, dynamic>;

        String? token;
        if (data.containsKey('token')) {
          token = data['token'] as String?;
        } else if (data.containsKey('access_token')) {
          token = data['access_token'] as String?;
        } else if (data.containsKey('data')) {
          final inner = data['data'] as Map?;
          token = inner?['token'] as String?;
        }

        if (token != null && token.isNotEmpty) {
          _token = token;
          debugPrint('✅ Login success. Token: ${_token?.substring(0, 12)}...');
          return true;
        } else {
          _errorMessage = 'لم يتم استلام رمز الدخول.';
        }
      } else if (response.statusCode == 401 || response.statusCode == 422) {
        final msg = _extractErrorMessage(response.body);
        _errorMessage =
            msg.contains('Invalid') ||
                    msg.contains('not found') ||
                    msg.contains('غير صحيحة')
                ? 'البريد أو كلمة المرور غير صحيحة'
                : msg;
      } else {
        _errorMessage = 'خطأ ($response.statusCode). حاول لاحقاً.';
      }
    } catch (e) {
      _errorMessage = 'فشل الاتصال. تحقق من الإنترنت.';
      if (kDebugMode) debugPrint('Network error: $e');
    } finally {
      setLoading(false);
      notifyListeners();
    }

    return false;
  }

  String _extractErrorMessage(String responseBody) {
    try {
      final data = jsonDecode(responseBody) as Map<String, dynamic>;
      return data['message']?.toString() ??
          data['error']?.toString() ??
          data.values.firstWhere((v) => v is String, orElse: () => 'حدث خطأ')
              as String;
    } catch (_) {
      return 'بيانات غير صحيحة';
    }
  }

  void logout() {
    _token = null;
    _errorMessage = null;
    emailController.clear();
    passwordController.clear();
    notifyListeners();
  }

  bool get isLoggedIn => _token != null;

  Future<Map<String, String>> getAuthHeaders() async {
    return {
      'Content-Type': 'application/json',
      if (_token != null) 'Authorization': 'Bearer $_token',
    };
  }

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }
}

import 'package:flutter/material.dart';

class WelcomeProvider extends ChangeNotifier {
  bool _isLoading = false;
  bool get isLoading => _isLoading;

  void setLoading(bool v) {
    _isLoading = v;
    notifyListeners();
  }

  Future<void> createAccount(BuildContext context) async {
    setLoading(true);
    await Future.delayed(const Duration(milliseconds: 800));
    setLoading(false);
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(const SnackBar(content: Text('اذهب إلى شاشة إنشاء حساب')));
  }

  Future<void> login(BuildContext context) async {
    setLoading(true);
    await Future.delayed(const Duration(milliseconds: 600));
    setLoading(false);
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(const SnackBar(content: Text('اذهب إلى شاشة تسجيل الدخول')));
  }

  Future<void> guestLogin(BuildContext context) async {
    setLoading(true);
    await Future.delayed(const Duration(milliseconds: 500));
    setLoading(false);
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(const SnackBar(content: Text('تم الدخول كزائر')));
  }

  Future<void> socialLogin(String provider, BuildContext context) async {
    setLoading(true);
    await Future.delayed(const Duration(milliseconds: 700));
    setLoading(false);
    ScaffoldMessenger.of(
      context,
    ).showSnackBar(SnackBar(content: Text('تسجيل عبر: $provider')));
  }
}

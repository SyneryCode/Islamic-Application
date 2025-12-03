import 'package:flutter/material.dart';

class LoginProvider extends ChangeNotifier {
  bool _isLoading = false;
  bool get isLoading => _isLoading;

  void setLoading(bool v) {
    _isLoading = v;
    notifyListeners();
  }

  Future<void> login(String email, String password) async {
    setLoading(true);

    await Future.delayed(const Duration(milliseconds: 600));
    setLoading(false);
  }
}

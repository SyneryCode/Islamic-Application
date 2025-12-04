// lib/features/auth/presentation/providers/register_provider.dart

import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class RegisterProvider extends ChangeNotifier {
  final TextEditingController fullNameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController confirmPasswordController =
      TextEditingController();
  final TextEditingController phoneController = TextEditingController();

  bool isPasswordVisible = false;
  bool isConfirmPasswordVisible = false;
  bool agreeToTerms = false;

  bool isSubmitting = false;
  String? errorMessage;

  static const String _baseUrl = 'https://islamic-application.onrender.com';
  static const String _registerEndpoint = '/api/auth/register';

  void togglePasswordVisibility() {
    isPasswordVisible = !isPasswordVisible;
    notifyListeners();
  }

  void toggleConfirmPasswordVisibility() {
    isConfirmPasswordVisible = !isConfirmPasswordVisible;
    notifyListeners();
  }

  void toggleAgreement() {
    agreeToTerms = !agreeToTerms;
    notifyListeners();
  }

  Future<void> submitRegistration() async {
    errorMessage = null;
    isSubmitting = true;
    notifyListeners();

    try {
      final validationError = _validateInputs();
      if (validationError != null) {
        errorMessage = validationError;
        return;
      }

      final url = Uri.parse('$_baseUrl$_registerEndpoint');
      final body = {
        'name': fullNameController.text.trim(),
        'email': emailController.text.trim(),
        'password': passwordController.text,
        'password_confirmation': confirmPasswordController.text,
        if (phoneController.text.trim().isNotEmpty)
          'phone': phoneController.text.trim(),
      };

      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(body),
      );

      if (response.statusCode == 201) {
        // نجاح
        errorMessage = 'تم إنشاء الحساب بنجاح!';
        // يمكنك إضافة: Navigator.pushReplacement(...) هنا لاحقاً
      } else if (response.statusCode == 422) {
        // أخطاء تحقق من الـ backend
        final data = jsonDecode(response.body) as Map<String, dynamic>;
        final errors = data['errors'] as Map<String, dynamic>?;
        if (errors != null && errors.isNotEmpty) {
          final firstField = errors.keys.first;
          final firstMsg = (errors[firstField] as List).first.toString();
          errorMessage = _mapApiErrorToArabic(firstField, firstMsg);
        } else {
          errorMessage = 'يرجى مراجعة البيانات.';
        }
      } else {
        errorMessage = 'حدث خطأ. حاول لاحقاً.';
      }
    } catch (e) {
      errorMessage = 'فشل الاتصال. تحقق من الإنترنت.';
      debugPrint('Error: $e');
    } finally {
      isSubmitting = false;
      notifyListeners();
    }
  }

  String? _validateInputs() {
    if (fullNameController.text.trim().isEmpty) return 'الاسم الكامل مطلوب';
    if (emailController.text.trim().isEmpty) return 'البريد الإلكتروني مطلوب';
    if (!RegExp(r'^[^@]+@[^@]+\.[^@]+').hasMatch(emailController.text)) {
      return 'بريد إلكتروني غير صالح';
    }
    if (passwordController.text.length < 6) return 'كلمة المرور قصيرة جداً';
    if (passwordController.text != confirmPasswordController.text) {
      return 'كلمتا المرور غير متطابقتين';
    }
    if (!agreeToTerms) return 'يجب الموافقة على الشروط';
    return null;
  }

  String _mapApiErrorToArabic(String field, String msg) {
    switch (field) {
      case 'email':
        return msg.contains('taken')
            ? 'البريد مُستخدم مسبقاً'
            : 'بريد غير صالح';
      case 'password':
        if (msg.contains('confirmation')) return 'كلمتا المرور غير متطابقتين';
        if (msg.contains('short')) return 'كلمة المرور قصيرة';
        return 'كلمة المرور غير صالحة';
      case 'name':
        return 'الاسم غير صالح';
      case 'phone':
        return 'رقم الهاتف غير صالح';
      default:
        return msg;
    }
  }

  // دوال قوة كلمة المرور (اختيارية لكن مفيدة)
  double getPasswordStrength() {
    final p = passwordController.text;
    if (p.isEmpty) return 0.0;
    if (p.length < 6) return 0.2;
    if (p.length < 8) return 0.4;
    final hasUpper = p.contains(RegExp(r'[A-Z]'));
    final hasDigit = p.contains(RegExp(r'[0-9]'));
    final hasSpecial = p.contains(RegExp(r'[!@#\$%^&*]'));
    return (hasUpper && hasDigit && hasSpecial)
        ? 1.0
        : (hasUpper || hasDigit)
        ? 0.7
        : 0.5;
  }

  String getPasswordStrengthLabel() {
    final s = getPasswordStrength();
    if (s < 0.3) return 'ضعيف';
    if (s < 0.6) return 'مقبول';
    if (s < 0.9) return 'جيد';
    return 'قوي';
  }

  Color getPasswordStrengthColor(BuildContext context) {
    final s = getPasswordStrength();
    if (s < 0.3) return Colors.red;
    if (s < 0.6) return Colors.orange;
    if (s < 0.9) return Colors.amber.shade700;
    return Theme.of(context).colorScheme.primary;
  }

  @override
  void dispose() {
    fullNameController.dispose();
    emailController.dispose();
    passwordController.dispose();
    confirmPasswordController.dispose();
    phoneController.dispose();
    super.dispose();
  }
}

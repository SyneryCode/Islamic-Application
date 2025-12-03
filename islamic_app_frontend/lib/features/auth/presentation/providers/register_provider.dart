import 'package:flutter/material.dart';

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
      await Future.delayed(const Duration(milliseconds: 800));

      if (fullNameController.text.trim().isEmpty) {
        errorMessage = 'الاسم الكامل مطلوب';
      } else if (!RegExp(
        r'^[^@]+@[^@]+\.[^@]+',
      ).hasMatch(emailController.text)) {
        errorMessage = 'يرجى إدخال بريد إلكتروني صالح';
      } else if (passwordController.text.length < 6) {
        errorMessage = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
      } else if (passwordController.text != confirmPasswordController.text) {
        errorMessage = 'كلمتا المرور غير متطابقتين';
      } else if (!agreeToTerms) {
        errorMessage = 'يجب الموافقة على الشروط والأحكام';
      }

      if (errorMessage != null) {
        return;
      }
    } catch (e) {
      errorMessage = 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.';
    } finally {
      isSubmitting = false;
      notifyListeners();
    }
  }

  double getPasswordStrength() {
    final password = passwordController.text;
    if (password.isEmpty) return 0.0;
    if (password.length < 6) return 0.2;
    if (password.length < 8) return 0.4;
    final hasUpper = password.contains(RegExp(r'[A-Z]'));
    final hasDigit = password.contains(RegExp(r'[0-9]'));
    final hasSpecial = password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'));
    return (hasUpper && hasDigit && hasSpecial)
        ? 1.0
        : (hasUpper || hasDigit)
        ? 0.7
        : 0.5;
  }

  String getPasswordStrengthLabel() {
    final strength = getPasswordStrength();
    if (strength < 0.3) return 'ضعيف';
    if (strength < 0.6) return 'مقبول';
    if (strength < 0.9) return 'جيد';
    return 'قوي';
  }

  Color getPasswordStrengthColor(BuildContext context) {
    final strength = getPasswordStrength();
    if (strength < 0.3) return Colors.red;
    if (strength < 0.6) return Colors.orange;
    if (strength < 0.9) return Colors.amber.shade700;
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

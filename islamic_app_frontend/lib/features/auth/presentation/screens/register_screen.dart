// lib/features/auth/presentation/screens/register_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/register_provider.dart';

class RegisterScreen extends StatelessWidget {
  const RegisterScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return ChangeNotifierProvider(
      create: (_) => RegisterProvider(),
      child: Scaffold(
        appBar: AppBar(
          title: const Text('إنشاء حساب'),
          centerTitle: true,
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        backgroundColor: Colors.grey[50],
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: Consumer<RegisterProvider>(
              builder: (context, provider, _) {
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    const SizedBox(height: 10),
                    Text(
                      "مرحبًا بك 👋",
                      style: theme.textTheme.headlineMedium?.copyWith(
                        color: Colors.amber[800],
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 5),
                    Text(
                      'أنشئ حسابك لتبدأ رحلتك الروحانية',
                      style: theme.textTheme.bodyMedium,
                    ),
                    const SizedBox(height: 30),

                    // الاسم الكامل
                    _buildInputField(
                      controller: provider.fullNameController,
                      label: "الاسم الكامل",
                      hint: "ادخل اسمك الكامل",
                      icon: Icons.person_outline,
                      errorText: _getErrorForField(provider, 'name'),
                    ),
                    const SizedBox(height: 16),

                    // البريد الإلكتروني
                    _buildInputField(
                      controller: provider.emailController,
                      label: "البريد الإلكتروني",
                      hint: "example@email.com",
                      icon: Icons.email_outlined,
                      errorText: _getErrorForField(provider, 'email'),
                    ),
                    const SizedBox(height: 16),

                    // كلمة المرور
                    _buildPasswordField(
                      controller: provider.passwordController,
                      label: "كلمة المرور",
                      hint: "••••••••",
                      isVisible: provider.isPasswordVisible,
                      onToggle: provider.togglePasswordVisibility,
                    ),
                    const SizedBox(height: 4),
                    _buildPasswordStrengthBar(provider, context),
                    const SizedBox(height: 16),

                    // تأكيد كلمة المرور
                    _buildPasswordField(
                      controller: provider.confirmPasswordController,
                      label: "تأكيد كلمة المرور",
                      hint: "أعد إدخال كلمة المرور",
                      isVisible: provider.isConfirmPasswordVisible,
                      onToggle: provider.toggleConfirmPasswordVisibility,
                      errorText:
                          provider.passwordController.text !=
                                      provider.confirmPasswordController.text &&
                                  provider
                                      .confirmPasswordController
                                      .text
                                      .isNotEmpty
                              ? 'كلمتا المرور غير متطابقتين'
                              : null,
                    ),
                    const SizedBox(height: 16),

                    // رقم الهاتف
                    _buildInputField(
                      controller: provider.phoneController,
                      label: "رقم الهاتف (اختياري)",
                      hint: "05XXXXXXXX",
                      icon: Icons.phone_outlined,
                      keyboardType: TextInputType.phone,
                    ),
                    const SizedBox(height: 20),

                    // الموافقة على الشروط
                    _buildAgreementSection(provider, context),
                    const SizedBox(height: 20),

                    // رسالة خطأ عامة (من الـ API أو التحقق)
                    if (provider.errorMessage != null &&
                        !provider.errorMessage!.contains('بنجاح'))
                      _buildErrorMessage(provider.errorMessage!),
                    const SizedBox(height: 10),

                    // زر إنشاء الحساب
                    SizedBox(
                      width: double.infinity,
                      height: 56,
                      child: ElevatedButton.icon(
                        icon:
                            provider.isSubmitting
                                ? const SizedBox(
                                  height: 20,
                                  width: 20,
                                  child: CircularProgressIndicator(
                                    strokeWidth: 2,
                                    color: Colors.white,
                                  ),
                                )
                                : const Icon(
                                  Icons.arrow_forward_ios_rounded,
                                  size: 18,
                                ),
                        label: Text(
                          provider.isSubmitting
                              ? 'جاري الإنشاء...'
                              : 'إنشاء الحساب',
                          style: const TextStyle(
                            fontSize: 17,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.green[700],
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(14),
                          ),
                        ),
                        onPressed:
                            provider.agreeToTerms && !provider.isSubmitting
                                ? () async {
                                  await provider.submitRegistration();
                                  if (provider.errorMessage != null) {
                                    // عرض الخطأ في SnackBar أو تحت الزر (تم بالفعل أعلاه)
                                  } else {
                                    // ✅ نجاح — انتقل للشاشة التالية
                                    // مثال: Navigator.pushReplacementNamed(context, '/home');
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      const SnackBar(
                                        content: Text(
                                          'تم إنشاء الحساب بنجاح! 🎉',
                                        ),
                                        backgroundColor: Colors.green,
                                      ),
                                    );
                                    // يمكنك هنا الانتقال للشاشة الرئيسية بعد 1.5 ثانية
                                    Future.delayed(
                                      const Duration(seconds: 1),
                                      () {
                                        Navigator.pop(
                                          context,
                                        ); // العودة لـ WelcomeScreen أو الذهاب لـ Home
                                      },
                                    );
                                  }
                                }
                                : null,
                      ),
                    ),

                    const SizedBox(height: 20),

                    // رابط تسجيل الدخول
                    Center(
                      child: GestureDetector(
                        onTap: () => Navigator.pop(context),
                        child: RichText(
                          text: TextSpan(
                            text: 'لديك حساب؟ ',
                            style: theme.textTheme.bodyMedium,
                            children: [
                              TextSpan(
                                text: 'تسجيل الدخول',
                                style: TextStyle(
                                  color: Colors.green[700],
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                  ],
                );
              },
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildInputField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    String? errorText,
    TextInputType keyboardType = TextInputType.text,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(
          label,
          style: const TextStyle(fontWeight: FontWeight.w500),
          textAlign: TextAlign.right,
        ),
        const SizedBox(height: 6),
        TextField(
          controller: controller,
          textAlign: TextAlign.right,
          keyboardType: keyboardType,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: Icon(icon),
            errorText: errorText,
            errorStyle: const TextStyle(height: 0.9),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide(
                color: errorText != null ? Colors.red : Colors.grey,
              ),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: const BorderSide(color: Colors.green),
            ),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
              vertical: 14,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildPasswordField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required bool isVisible,
    required VoidCallback onToggle,
    String? errorText,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        Text(
          label,
          style: const TextStyle(fontWeight: FontWeight.w500),
          textAlign: TextAlign.right,
        ),
        const SizedBox(height: 6),
        TextField(
          controller: controller,
          obscureText: !isVisible,
          textAlign: TextAlign.right,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: const Icon(Icons.lock_outline),
            suffixIcon: IconButton(
              icon: Icon(
                isVisible ? Icons.visibility : Icons.visibility_off_outlined,
                color: Colors.grey[600],
              ),
              onPressed: onToggle,
            ),
            errorText: errorText,
            errorStyle: const TextStyle(height: 0.9),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide(
                color: errorText != null ? Colors.red : Colors.grey,
              ),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: const BorderSide(color: Colors.green),
            ),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
              vertical: 14,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildPasswordStrengthBar(
    RegisterProvider provider,
    BuildContext context,
  ) {
    final strength = provider.getPasswordStrength();
    final label = provider.getPasswordStrengthLabel();
    final color = provider.getPasswordStrengthColor(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.end,
      children: [
        LinearProgressIndicator(
          value: strength,
          color: color,
          backgroundColor: Colors.grey[300],
          minHeight: 4,
          borderRadius: BorderRadius.circular(20),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: TextStyle(
            color: color,
            fontSize: 12,
            fontWeight: FontWeight.w500,
          ),
          textAlign: TextAlign.right,
        ),
      ],
    );
  }

  Widget _buildAgreementSection(
    RegisterProvider provider,
    BuildContext context,
  ) {
    final theme = Theme.of(context);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Checkbox(
              value: provider.agreeToTerms,
              onChanged: (_) => provider.toggleAgreement(),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(4),
              ),
            ),
            Expanded(
              child: Text.rich(
                TextSpan(
                  text: "أوافق على ",
                  style: theme.textTheme.bodyMedium,
                  children: [
                    TextSpan(
                      text: "الشروط والأحكام",
                      style: const TextStyle(
                        color: Colors.amber,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const TextSpan(text: " و "),
                    TextSpan(
                      text: "سياسة الخصوصية",
                      style: const TextStyle(
                        color: Colors.amber,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    const TextSpan(text: "."),
                  ],
                ),
                textAlign: TextAlign.right,
              ),
            ),
          ],
        ),
        Align(
          alignment: Alignment.centerRight,
          child: TextButton.icon(
            onPressed: () {
              // TODO: فتح صفحة الشروط
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('سيتم إضافة صفحة الشروط لاحقاً')),
              );
            },
            icon: const Icon(Icons.description_outlined, color: Colors.amber),
            label: const Text(
              "عرض الشروط",
              style: TextStyle(color: Colors.amber, fontSize: 14),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildErrorMessage(String message) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.red.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.red.withOpacity(0.3)),
      ),
      child: Row(
        children: [
          const Icon(Icons.error_outline, color: Colors.red, size: 20),
          const SizedBox(width: 8),
          Expanded(
            child: Text(
              message,
              style: const TextStyle(color: Colors.red, fontSize: 14),
              textAlign: TextAlign.right,
            ),
          ),
        ],
      ),
    );
  }

  // دالة مساعدة لاستخراج أخطاء الحقول من رسالة الـ API
  String? _getErrorForField(RegisterProvider provider, String field) {
    final msg = provider.errorMessage;
    if (msg == null || msg.contains('بنجاح')) return null;

    // إذا كانت الرسالة تحتوي على اسم الحقل (مثلاً من الـ API)
    if (field == 'email' && msg.contains('البريد')) return msg;
    if (field == 'name' && msg.contains('الاسم')) return msg;

    // يمكنك توسيعها لاحقاً لدعم أخطاء مفصّلة
    return null;
  }
}

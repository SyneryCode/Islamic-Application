// lib/features/auth/presentation/screens/login_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/login_provider.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => LoginProvider(),
      child: const _LoginBody(),
    );
  }
}

class _LoginBody extends StatelessWidget {
  const _LoginBody();

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<LoginProvider>(context);
    final theme = Theme.of(context);

    return Directionality(
      textDirection: TextDirection.rtl,
      child: Scaffold(
        appBar: AppBar(
          title: const Text('تسجيل الدخول'),
          centerTitle: true,
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        body: SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const SizedBox(height: 10),
                  Text(
                    'مرحبًا مجددًا 👋',
                    style: theme.textTheme.headlineMedium?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: Colors.amber[800],
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text('سُعداء بعودتك', style: theme.textTheme.bodyMedium),
                  const SizedBox(height: 30),

                  // البريد الإلكتروني
                  _buildInputField(
                    controller:
                        provider.emailController ?? TextEditingController(),
                    label: "البريد الإلكتروني",
                    hint: "example@email.com",
                    icon: Icons.email_outlined,
                    errorText: _getErrorForField(provider, 'email'),
                    onChanged: (value) => provider.clearError(),
                  ),
                  const SizedBox(height: 16),

                  // كلمة المرور
                  _buildPasswordField(
                    controller:
                        provider.passwordController ?? TextEditingController(),
                    label: "كلمة المرور",
                    hint: "••••••••",
                    isVisible: provider.isPasswordVisible,
                    onToggle: provider.togglePasswordVisibility,
                    errorText: _getErrorForField(provider, 'password'),
                    onChanged: (value) => provider.clearError(),
                  ),
                  const SizedBox(height: 6),

                  Align(
                    alignment: Alignment.centerRight,
                    child: TextButton(
                      onPressed: () {
                        // TODO: فتح شاشة "نسيت كلمة المرور"
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('سيتم إضافة هذه الميزة لاحقاً'),
                          ),
                        );
                      },
                      child: const Text(
                        'نسيت كلمة المرور؟',
                        style: TextStyle(color: Colors.green, fontSize: 15),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),

                  // رسالة خطأ عامة
                  if (provider.errorMessage != null)
                    _buildErrorMessage(provider.errorMessage!),
                  const SizedBox(height: 10),

                  // زر تسجيل الدخول
                  SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: ElevatedButton.icon(
                      icon:
                          provider.isLoading
                              ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(
                                  strokeWidth: 2,
                                  color: Colors.white,
                                ),
                              )
                              : const Icon(Icons.login, size: 18),
                      label: Text(
                        provider.isLoading ? 'جاري الدخول...' : 'تسجيل الدخول',
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
                          provider.isLoading
                              ? null
                              : () async {
                                final success = await provider.login(
                                  provider.emailController.text,
                                  provider.passwordController.text,
                                );
                                if (success) {
                                  ScaffoldMessenger.of(context).showSnackBar(
                                    const SnackBar(
                                      content: Text('مرحبًا بك مجددًا! 🌿'),
                                      backgroundColor: Colors.green,
                                    ),
                                  );
                                  // ✅ الانتقال للشاشة الرئيسية
                                  // Navigator.pushReplacementNamed(context, '/home');
                                  // أو العودة للترحيب (إذا كنت تريد فقط اختبار الدخول)
                                  Navigator.pop(context);
                                }
                                // إذا فشل، الرسالة تظهر تلقائياً في `_buildErrorMessage`
                              },
                    ),
                  ),

                  const SizedBox(height: 25),

                  // رابط إنشاء حساب
                  Center(
                    child: GestureDetector(
                      onTap: () {
                        Navigator.pushNamed(context, '/register');
                      },
                      child: RichText(
                        text: TextSpan(
                          text: 'ليس لديك حساب؟ ',
                          style: theme.textTheme.bodyMedium,
                          children: [
                            TextSpan(
                              text: 'إنشاء حساب',
                              style: TextStyle(
                                color: Colors.amber[800],
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                ],
              ),
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
    required void Function(String) onChanged,
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
          keyboardType: TextInputType.emailAddress,
          onChanged: onChanged,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: Icon(
              icon,
              color: errorText != null ? Colors.red : null,
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

  Widget _buildPasswordField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required bool isVisible,
    required VoidCallback onToggle,
    String? errorText,
    required void Function(String) onChanged,
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
          onChanged: onChanged,
          decoration: InputDecoration(
            hintText: hint,
            prefixIcon: const Icon(Icons.lock_outline),
            suffixIcon: IconButton(
              icon: Icon(
                isVisible ? Icons.visibility : Icons.visibility_off_outlined,
                color: errorText != null ? Colors.red : Colors.grey[600],
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

  String? _getErrorForField(LoginProvider provider, String field) {
    final msg = provider.errorMessage;
    if (msg == null) return null;
    if (field == 'email' && (msg.contains('البريد') || msg.contains('email')))
      return msg;
    if (field == 'password' && msg.contains('كلمة المرور')) return msg;
    return null;
  }
}

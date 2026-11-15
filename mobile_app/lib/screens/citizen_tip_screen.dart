import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../providers/app_provider.dart';

class CitizenTipScreen extends StatefulWidget {
  const CitizenTipScreen({super.key});

  @override
  State<CitizenTipScreen> createState() => _CitizenTipScreenState();
}

class _CitizenTipScreenState extends State<CitizenTipScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _descController = TextEditingController();
  final TextEditingController _locationController = TextEditingController();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();

  bool _isAnonymous = false;
  File? _selectedMedia;
  bool _isSubmitting = false;

  final ImagePicker _picker = ImagePicker();

  Future<void> _pickImage(ImageSource source) async {
    final picked = await _picker.pickImage(source: source, imageQuality: 80);
    if (picked != null) {
      setState(() => _selectedMedia = File(picked.path));
    }
  }

  Future<void> _submitTip() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    final api = Provider.of<AppProvider>(context, listen: false).apiService;

    try {
      final success = await api.submitCitizenTip(
        title: _titleController.text.trim(),
        description: _descController.text.trim(),
        locationName: _locationController.text.trim(),
        witnessName: _isAnonymous ? null : _nameController.text.trim(),
        witnessPhone: _isAnonymous ? null : _phoneController.text.trim(),
        isAnonymous: _isAnonymous,
        mediaFile: _selectedMedia,
      );

      if (mounted) {
        setState(() => _isSubmitting = false);
        if (success) {
          showDialog(
            context: context,
            builder: (_) => AlertDialog(
              title: const Text('Tip Submitted Successfully!'),
              content: const Text(
                'Thank you for your eyewitness reporting. Our editorial desk will verify and review your submission.',
              ),
              actions: [
                TextButton(
                  child: const Text('OK'),
                  onPressed: () {
                    Navigator.pop(context);
                    Navigator.pop(context);
                  },
                ),
              ],
            ),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Failed to submit tip. Please check your network.')),
          );
        }
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isSubmitting = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $e')),
        );
      }
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descController.dispose();
    _locationController.dispose();
    _nameController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Citizen Journalism Desk', style: TextStyle(fontWeight: FontWeight.w900)),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFE11D48).withOpacity(0.08),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE11D48).withOpacity(0.2)),
                ),
                child: Row(
                  children: const [
                    Icon(Icons.shield_outlined, color: Color(0xFFE11D48)),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Report ground updates or breaking incidents directly to our newsroom. Your identity can remain 100% confidential.',
                        style: TextStyle(fontSize: 12, height: 1.4),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),

              // Title
              const Text('What happened? (Headline) *', style: TextStyle(fontWeight: FontWeight.w700)),
              const SizedBox(height: 8),
              TextFormField(
                controller: _titleController,
                decoration: const InputDecoration(
                  hintText: 'e.g. Major waterlogging on Highway near Metro Station',
                  border: OutlineInputBorder(borderRadius: BorderRadius.all(Radius.circular(12))),
                ),
                validator: (val) => val == null || val.isEmpty ? 'Please enter a title' : null,
              ),
              const SizedBox(height: 16),

              // Description
              const Text('Detailed Ground Report *', style: TextStyle(fontWeight: FontWeight.w700)),
              const SizedBox(height: 8),
              TextFormField(
                controller: _descController,
                maxLines: 4,
                decoration: const InputDecoration(
                  hintText: 'Describe what you witnessed, timeline, and impact...',
                  border: OutlineInputBorder(borderRadius: BorderRadius.all(Radius.circular(12))),
                ),
                validator: (val) => val == null || val.isEmpty ? 'Please provide details' : null,
              ),
              const SizedBox(height: 16),

              // Location
              const Text('Location / Neighborhood', style: TextStyle(fontWeight: FontWeight.w700)),
              const SizedBox(height: 8),
              TextFormField(
                controller: _locationController,
                decoration: const InputDecoration(
                  hintText: 'e.g. MG Road, Ward 4',
                  prefixIcon: Icon(Icons.location_on_outlined),
                  border: OutlineInputBorder(borderRadius: BorderRadius.all(Radius.circular(12))),
                ),
              ),
              const SizedBox(height: 20),

              // Media Upload
              const Text('Attach Photo or Proof', style: TextStyle(fontWeight: FontWeight.w700)),
              const SizedBox(height: 8),
              if (_selectedMedia != null)
                Stack(
                  children: [
                    ClipRRect(
                      borderRadius: BorderRadius.circular(12),
                      child: Image.file(_selectedMedia!, height: 180, width: double.infinity, fit: BoxFit.cover),
                    ),
                    Positioned(
                      top: 8,
                      right: 8,
                      child: IconButton(
                        icon: const Icon(Icons.cancel, color: Colors.white, size: 28),
                        onPressed: () => setState(() => _selectedMedia = null),
                      ),
                    ),
                  ],
                )
              else
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton.icon(
                        icon: const Icon(Icons.camera_alt),
                        label: const Text('Camera'),
                        onPressed: () => _pickImage(ImageSource.camera),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: OutlinedButton.icon(
                        icon: const Icon(Icons.photo_library),
                        label: const Text('Gallery'),
                        onPressed: () => _pickImage(ImageSource.gallery),
                      ),
                    ),
                  ],
                ),
              const SizedBox(height: 20),

              // Anonymous Checkbox
              SwitchListTile(
                contentPadding: EdgeInsets.zero,
                activeColor: const Color(0xFFE11D48),
                title: const Text('Keep My Identity Completely Anonymous', style: TextStyle(fontWeight: FontWeight.w700)),
                subtitle: const Text('Your name and phone will not be shared or stored publicly.', style: TextStyle(fontSize: 12)),
                value: _isAnonymous,
                onChanged: (val) => setState(() => _isAnonymous = val),
              ),
              const SizedBox(height: 12),

              if (!_isAnonymous) ...[
                const Text('Your Name', style: TextStyle(fontWeight: FontWeight.w700)),
                const SizedBox(height: 8),
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(
                    hintText: 'John Doe',
                    border: OutlineInputBorder(borderRadius: BorderRadius.all(Radius.circular(12))),
                  ),
                ),
                const SizedBox(height: 12),
                const Text('Your Phone Number', style: TextStyle(fontWeight: FontWeight.w700)),
                const SizedBox(height: 8),
                TextFormField(
                  controller: _phoneController,
                  keyboardType: TextInputType.phone,
                  decoration: const InputDecoration(
                    hintText: '+1 234 567 8900',
                    border: OutlineInputBorder(borderRadius: BorderRadius.all(Radius.circular(12))),
                  ),
                ),
                const SizedBox(height: 24),
              ],

              // Submit Button
              SizedBox(
                width: double.infinity,
                height: 52,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFFE11D48),
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  ),
                  onPressed: _isSubmitting ? null : _submitTip,
                  child: _isSubmitting
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'SUBMIT GROUND REPORT',
                          style: TextStyle(fontWeight: FontWeight.w900, letterSpacing: 0.5, fontSize: 14),
                        ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

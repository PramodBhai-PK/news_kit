import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/location_model.dart';
import '../providers/app_provider.dart';
import '../providers/news_provider.dart';
import '../services/api_service.dart';

class CitySelectorSheet extends StatefulWidget {
  const CitySelectorSheet({super.key});

  @override
  State<CitySelectorSheet> createState() => _CitySelectorSheetState();
}

class _CitySelectorSheetState extends State<CitySelectorSheet> {
  List<CityModel> _cities = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadCities();
  }

  Future<void> _loadCities() async {
    final apiService = Provider.of<AppProvider>(context, listen: false).apiService;
    try {
      final cities = await apiService.fetchCities();
      if (mounted) {
        setState(() {
          _cities = cities;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final appProvider = Provider.of<AppProvider>(context);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF0F172A) : Colors.white,
        borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Row(
                children: [
                  Icon(Icons.location_on, color: Color(0xFFE11D48)),
                  SizedBox(width: 8),
                  Text(
                    'Select Your City / District',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800),
                  ),
                ],
              ),
              IconButton(
                icon: const Icon(Icons.close),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),
          const SizedBox(height: 12),
          // Clear Location Button
          if (appProvider.selectedCitySlug != null)
            Padding(
              padding: const EdgeInsets.only(bottom: 12),
              child: OutlinedButton.icon(
                icon: const Icon(Icons.clear, size: 16),
                label: const Text('Show All National News (Clear City)'),
                style: OutlinedButton.styleFrom(
                  foregroundColor: const Color(0xFFE11D48),
                  side: const BorderSide(color: Color(0xFFE11D48)),
                ),
                onPressed: () {
                  appProvider.setSelectedCity(null, null);
                  Provider.of<NewsProvider>(context, listen: false).initHomeData();
                  Navigator.pop(context);
                },
              ),
            ),
          // City List
          if (_isLoading)
            const Center(child: Padding(padding: EdgeInsets.all(32), child: CircularProgressIndicator()))
          else
            ConstrainedBox(
              constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.4),
              child: ListView.separated(
                shrinkWrap: true,
                itemCount: _cities.length,
                separatorBuilder: (_, __) => const Divider(height: 1),
                itemBuilder: (context, index) {
                  final city = _cities[index];
                  final isSelected = city.slug == appProvider.selectedCitySlug;

                  return ListTile(
                    contentPadding: EdgeInsets.zero,
                    title: Text(
                      city.name,
                      style: TextStyle(
                        fontWeight: isSelected ? FontWeight.w800 : FontWeight.w600,
                        color: isSelected ? const Color(0xFFE11D48) : null,
                      ),
                    ),
                    subtitle: city.stateName != null ? Text(city.stateName!) : null,
                    trailing: isSelected
                        ? const Icon(Icons.check_circle, color: Color(0xFFE11D48))
                        : (city.articlesCount > 0
                            ? Text('${city.articlesCount} news', style: const TextStyle(fontSize: 12, color: Colors.grey))
                            : null),
                    onTap: () {
                      appProvider.setSelectedCity(city.slug, city.name);
                      Provider.of<NewsProvider>(context, listen: false).initHomeData(citySlug: city.slug);
                      Navigator.pop(context);
                    },
                  );
                },
              ),
            ),
        ],
      ),
    );
  }
}


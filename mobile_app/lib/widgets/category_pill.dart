import 'package:flutter/material.dart';
import '../models/category_model.dart';

class CategoryPillList extends StatelessWidget {
  final List<CategoryModel> categories;
  final String? activeCategorySlug;
  final Function(String?) onCategorySelected;

  const CategoryPillList({
    super.key,
    required this.categories,
    required this.activeCategorySlug,
    required this.onCategorySelected,
  });

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return SizedBox(
      height: 40,
      child: ListView.separated(
        padding: const EdgeInsets.symmetric(horizontal: 16),
        scrollDirection: Axis.horizontal,
        itemCount: categories.length + 1,
        separatorBuilder: (_, __) => const SizedBox(width: 8),
        itemBuilder: (context, index) {
          final isAll = index == 0;
          final isSelected = isAll
              ? activeCategorySlug == null
              : activeCategorySlug == categories[index - 1].slug;

          final label = isAll ? 'ALL' : categories[index - 1].name;

          return InkWell(
            onTap: () {
              onCategorySelected(isAll ? null : categories[index - 1].slug);
            },
            borderRadius: BorderRadius.circular(20),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: isSelected
                    ? const Color(0xFFE11D48)
                    : (isDark ? const Color(0xFF1E293B) : const Color(0xFFE2E8F0)),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Center(
                child: Text(
                  label.toUpperCase(),
                  style: TextStyle(
                    color: isSelected
                        ? Colors.white
                        : (isDark ? Colors.grey.shade300 : const Color(0xFF334155)),
                    fontSize: 11,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 0.5,
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}


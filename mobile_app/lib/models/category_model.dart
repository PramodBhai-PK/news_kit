class CategoryModel {
  final int id;
  final String name;
  final String displayName;
  final String slug;
  final String color;
  final String? icon;
  final int order;
  final int articlesCount;

  CategoryModel({
    required this.id,
    required this.name,
    required this.displayName,
    required this.slug,
    required this.color,
    this.icon,
    this.order = 0,
    this.articlesCount = 0,
  });

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      displayName: json['display_name'] ?? json['name'] ?? '',
      slug: json['slug'] ?? '',
      color: json['color'] ?? '#E11D48',
      icon: json['icon'],
      order: json['order'] ?? 0,
      articlesCount: json['articles_count'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'display_name': displayName,
    'slug': slug,
    'color': color,
    'icon': icon,
    'order': order,
    'articles_count': articlesCount,
  };
}

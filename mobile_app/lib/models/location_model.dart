class StateModel {
  final int id;
  final String name;
  final String displayName;
  final String slug;
  final String? code;
  final int order;
  final int citiesCount;
  final List<CityModel> cities;

  StateModel({
    required this.id,
    required this.name,
    required this.displayName,
    required this.slug,
    this.code,
    this.order = 0,
    this.citiesCount = 0,
    this.cities = const [],
  });

  factory StateModel.fromJson(Map<String, dynamic> json) {
    return StateModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      displayName: json['display_name'] ?? json['name'] ?? '',
      slug: json['slug'] ?? '',
      code: json['code'],
      order: json['order'] ?? 0,
      citiesCount: json['cities_count'] ?? 0,
      cities: (json['cities'] as List<dynamic>?)
              ?.map((c) => CityModel.fromJson(c))
              .toList() ??
          [],
    );
  }
}

class CityModel {
  final int id;
  final String name;
  final String displayName;
  final String slug;
  final int stateId;
  final String? stateName;
  final int order;
  final int articlesCount;

  CityModel({
    required this.id,
    required this.name,
    required this.displayName,
    required this.slug,
    required this.stateId,
    this.stateName,
    this.order = 0,
    this.articlesCount = 0,
  });

  factory CityModel.fromJson(Map<String, dynamic> json) {
    return CityModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      displayName: json['display_name'] ?? json['name'] ?? '',
      slug: json['slug'] ?? '',
      stateId: json['state_id'] ?? 0,
      stateName: json['state_name'],
      order: json['order'] ?? 0,
      articlesCount: json['articles_count'] ?? 0,
    );
  }
}

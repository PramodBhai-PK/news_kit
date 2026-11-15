class UserModel {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;
  final String? bio;
  final String? avatar;
  final UserPressCard? pressCard;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
    this.bio,
    this.avatar,
    this.pressCard,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'],
      role: json['role'] ?? 'reader',
      bio: json['bio'],
      avatar: json['avatar'],
      pressCard: json['press_card'] != null ? UserPressCard.fromJson(json['press_card']) : null,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'email': email,
    'phone': phone,
    'role': role,
    'bio': bio,
    'avatar': avatar,
    'press_card': pressCard?.toJson(),
  };
}

class UserPressCard {
  final String code;
  final String? designation;
  final String? bloodGroup;
  final String? bureauLocation;
  final String status;
  final String? issuedAt;
  final String? expiresAt;
  final bool isValid;
  final String? verifyUrl;

  UserPressCard({
    required this.code,
    this.designation,
    this.bloodGroup,
    this.bureauLocation,
    required this.status,
    this.issuedAt,
    this.expiresAt,
    this.isValid = true,
    this.verifyUrl,
  });

  factory UserPressCard.fromJson(Map<String, dynamic> json) {
    return UserPressCard(
      code: json['code'] ?? '',
      designation: json['designation'],
      bloodGroup: json['blood_group'],
      bureauLocation: json['bureau_location'],
      status: json['status'] ?? 'issued',
      issuedAt: json['issued_at'],
      expiresAt: json['expires_at'],
      isValid: json['is_valid'] ?? true,
      verifyUrl: json['verify_url'],
    );
  }

  Map<String, dynamic> toJson() => {
    'code': code,
    'designation': designation,
    'blood_group': bloodGroup,
    'bureau_location': bureauLocation,
    'status': status,
    'issued_at': issuedAt,
    'expires_at': expiresAt,
    'is_valid': isValid,
    'verify_url': verifyUrl,
  };
}

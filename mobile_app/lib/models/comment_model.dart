class CommentModel {
  final int id;
  final String userName;
  final String comment;
  final String? createdAt;
  final String? createdAtHuman;

  CommentModel({
    required this.id,
    required this.userName,
    required this.comment,
    this.createdAt,
    this.createdAtHuman,
  });

  factory CommentModel.fromJson(Map<String, dynamic> json) {
    return CommentModel(
      id: json['id'] ?? 0,
      userName: json['user_name'] ?? 'Reader',
      comment: json['comment'] ?? '',
      createdAt: json['created_at'],
      createdAtHuman: json['created_at_human'],
    );
  }
}


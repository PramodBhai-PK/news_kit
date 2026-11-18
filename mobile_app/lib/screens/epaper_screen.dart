import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:intl/intl.dart';
import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import '../config/app_config.dart';
import '../models/epaper_model.dart';
import '../services/api_service.dart';

class EpaperScreen extends StatefulWidget {
  final int? initialEditionId;
  final String? initialDate;

  const EpaperScreen({
    super.key,
    this.initialEditionId,
    this.initialDate,
  });

  @override
  State<EpaperScreen> createState() => _EpaperScreenState();
}

class _EpaperScreenState extends State<EpaperScreen> {
  final ApiService _apiService = ApiService();
  late final PageController _pageController;

  EpaperEditionModel? _edition;
  List<EpaperEditionModel> _archiveEditions = [];
  bool _isLoading = true;
  String? _errorMessage;
  int _currentPageIndex = 0;
  DateTime _selectedDate = DateTime.now();

  // Zoom controllers per page
  final Map<int, TransformationController> _zoomControllers = {};

  @override
  void initState() {
    super.initState();
    _pageController = PageController();
    _loadInitialData();
  }

  @override
  void dispose() {
    _pageController.dispose();
    for (var controller in _zoomControllers.values) {
      controller.dispose();
    }
    super.dispose();
  }

  TransformationController _getZoomController(int pageIndex) {
    if (!_zoomControllers.containsKey(pageIndex)) {
      _zoomControllers[pageIndex] = TransformationController();
    }
    return _zoomControllers[pageIndex]!;
  }

  void _resetZoom(int pageIndex) {
    final controller = _getZoomController(pageIndex);
    controller.value = Matrix4.identity();
  }

  void _zoomIn(int pageIndex) {
    final controller = _getZoomController(pageIndex);
    final currentScale = controller.value.getMaxScaleOnAxis();
    if (currentScale < 4.0) {
      controller.value = Matrix4.identity()..scale(currentScale + 0.75);
    }
  }

  void _zoomOut(int pageIndex) {
    final controller = _getZoomController(pageIndex);
    final currentScale = controller.value.getMaxScaleOnAxis();
    if (currentScale > 1.25) {
      controller.value = Matrix4.identity()..scale(currentScale - 0.75);
    } else {
      controller.value = Matrix4.identity();
    }
  }

  Future<void> _loadInitialData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      EpaperEditionModel? edition;
      if (widget.initialEditionId != null) {
        edition = await _apiService.fetchEpaperEdition(widget.initialEditionId!);
      } else if (widget.initialDate != null) {
        final list = await _apiService.fetchEpaperEditions(date: widget.initialDate);
        if (list.isNotEmpty) edition = list.first;
      } else {
        edition = await _apiService.fetchTodayEpaper();
      }

      // Also prefetch recent editions for calendar/archive quick switch
      final archive = await _apiService.fetchEpaperEditions();

      if (mounted) {
        setState(() {
          _edition = edition;
          _archiveEditions = archive;
          _isLoading = false;
          _currentPageIndex = 0;
        });

        if (edition != null) {
          _apiService.recordEpaperView(edition.id);
        }
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isLoading = false;
          _errorMessage = 'Failed to load E-Paper. Please check your connection.';
        });
      }
    }
  }

  Future<void> _selectDate() async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate,
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: ColorScheme.fromSeed(
              seedColor: AppConfig.primaryColor,
              primary: AppConfig.primaryColor,
            ),
          ),
          child: child!,
        );
      },
    );

    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
        _isLoading = true;
      });

      final dateStr = DateFormat('yyyy-MM-dd').format(picked);
      final list = await _apiService.fetchEpaperEditions(date: dateStr);

      if (mounted) {
        setState(() {
          _edition = list.isNotEmpty ? list.first : null;
          _isLoading = false;
          _currentPageIndex = 0;
        });

        if (_edition != null) {
          _apiService.recordEpaperView(_edition!.id);
          if (_pageController.hasClients) {
            _pageController.jumpToPage(0);
          }
        }
      }
    }
  }

  void _downloadPdf() async {
    if (_edition?.pdfFile == null || _edition!.pdfFile!.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('PDF edition is not available for this date.')),
      );
      return;
    }

    _apiService.recordEpaperDownload(_edition!.id);

    final uri = Uri.parse(_edition!.pdfFile!);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Opening full edition PDF...')),
        );
      }
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Could not open PDF URL.')),
        );
      }
    }
  }

  void _shareCurrentPage() {
    if (_edition == null || _edition!.pages.isEmpty) return;

    final page = _edition!.pages[_currentPageIndex];
    final shareText = '🗞️ Read ${page.pageTitle} from ${_edition!.title} (${_edition!.formattedDate}) on ${AppConfig.appName}!\n\nView Digital Edition: ${page.pageImage}';

    Share.share(shareText, subject: '${_edition!.title} - ${page.pageTitle}');
  }

  void _showClippingSheet() {
    if (_edition == null || _edition!.pages.isEmpty) return;
    final page = _edition!.pages[_currentPageIndex];
    final isDark = Theme.of(context).brightness == Brightness.dark;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: isDark ? const Color(0xFF1E293B) : Colors.white,
            borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: Colors.grey.withOpacity(0.4),
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  const Text('✂️', style: TextStyle(fontSize: 20)),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'OFFICIAL ARTICLE CLIPPING',
                          style: TextStyle(fontWeight: FontWeight.w900, fontSize: 13, letterSpacing: 0.5),
                        ),
                        Text(
                          '${_edition!.title} • ${page.pageTitle}',
                          style: const TextStyle(fontSize: 11, color: Colors.grey),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Container(
                height: 220,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.redAccent.withOpacity(0.5), width: 2),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(10),
                  child: Stack(
                    fit: StackFit.expand,
                    children: [
                      CachedNetworkImage(
                        imageUrl: page.pageImage,
                        fit: BoxFit.cover,
                      ),
                      Positioned(
                        top: 0,
                        left: 0,
                        right: 0,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                          color: Colors.redAccent.withOpacity(0.85),
                          child: Text(
                            'CLIPPED STORY • ${_edition!.formattedDate}',
                            style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.w900),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF25D366),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: const Icon(Icons.chat, size: 16),
                      label: const Text('WhatsApp', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                      onPressed: () {
                        Navigator.pop(ctx);
                        final text = '🗞️ Read ${page.pageTitle} on ${AppConfig.appName}: ${page.pageImage}';
                        launchUrl(Uri.parse('whatsapp://send?text=${Uri.encodeComponent(text)}'), mode: LaunchMode.externalApplication).catchError((_) {
                          Share.share(text);
                          return true;
                        });
                      },
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: OutlinedButton.icon(
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: const Icon(Icons.share, size: 16),
                      label: const Text('Share All', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                      onPressed: () {
                        Navigator.pop(ctx);
                        _shareCurrentPage();
                      },
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
            ],
          ),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF0F172A) : const Color(0xFFF1F5F9),
      appBar: AppBar(
        elevation: 1,
        backgroundColor: isDark ? const Color(0xFF1E293B) : Colors.white,
        titleSpacing: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.newspaper, size: 18, color: AppConfig.primaryColor),
                const SizedBox(width: 6),
                Text(
                  _edition?.title ?? 'Digital E-Paper',
                  style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
            if (_edition != null)
              Text(
                '${_edition!.formattedDate} • ${_edition!.totalPages} Pages',
                style: const TextStyle(fontSize: 11, color: Colors.grey, fontWeight: FontWeight.w600),
              ),
          ],
        ),
        actions: [
          // Select Date / Archive
          IconButton(
            icon: const Icon(Icons.calendar_month, color: AppConfig.primaryColor),
            tooltip: 'Select Edition Date',
            onPressed: _selectDate,
          ),
          // Download PDF
          if (_edition?.pdfFile != null)
            IconButton(
              icon: const Icon(Icons.picture_as_pdf, color: Colors.redAccent),
              tooltip: 'Download PDF Edition',
              onPressed: _downloadPdf,
            ),
          // Clip & Share Article Tool
          if (_edition != null && _edition!.pages.isNotEmpty)
            IconButton(
              icon: const Icon(Icons.content_cut, color: Colors.amberAccent),
              tooltip: 'Clip & Share Story',
              onPressed: _showClippingSheet,
            ),
          // Share Page Link
          if (_edition != null && _edition!.pages.isNotEmpty)
            IconButton(
              icon: const Icon(Icons.share_outlined),
              tooltip: 'Share Page Link',
              onPressed: _shareCurrentPage,
            ),
          const SizedBox(width: 4),
        ],
      ),
      body: _buildBody(isDark),
      bottomNavigationBar: _edition != null && _edition!.pages.isNotEmpty
          ? _buildBottomBar(isDark)
          : null,
    );
  }

  Widget _buildBody(bool isDark) {
    if (_isLoading) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const CircularProgressIndicator(color: AppConfig.primaryColor),
            const SizedBox(height: 16),
            Text(
              'Loading Digital E-Paper Edition...',
              style: TextStyle(
                fontWeight: FontWeight.w700,
                color: isDark ? Colors.white70 : Colors.black87,
              ),
            ),
          ],
        ),
      );
    }

    if (_errorMessage != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.wifi_off_rounded, size: 64, color: Colors.grey),
              const SizedBox(height: 16),
              Text(
                _errorMessage!,
                textAlign: TextAlign.center,
                style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
              ),
              const SizedBox(height: 20),
              ElevatedButton.icon(
                style: ElevatedButton.styleFrom(backgroundColor: AppConfig.primaryColor),
                onPressed: _loadInitialData,
                icon: const Icon(Icons.refresh, color: Colors.white),
                label: const Text('Retry', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
      );
    }

    if (_edition == null || _edition!.pages.isEmpty) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.auto_stories, size: 64, color: Colors.grey),
              const SizedBox(height: 16),
              const Text(
                'No E-Paper Edition Found',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w900),
              ),
              const SizedBox(height: 8),
              Text(
                'No published digital edition is available for ${DateFormat('dd MMMM yyyy').format(_selectedDate)}.',
                textAlign: TextAlign.center,
                style: const TextStyle(fontSize: 13, color: Colors.grey),
              ),
              const SizedBox(height: 20),
              ElevatedButton.icon(
                style: ElevatedButton.styleFrom(backgroundColor: AppConfig.primaryColor),
                onPressed: () {
                  setState(() => _selectedDate = DateTime.now());
                  _loadInitialData();
                },
                icon: const Icon(Icons.today, color: Colors.white),
                label: const Text('Read Today\'s Edition', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
      );
    }

    return Column(
      children: [
        // Top Page Info & Quick Controls Bar
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          color: isDark ? const Color(0xFF1E293B) : Colors.white,
          child: Row(
            children: [
              // Page badge
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppConfig.primaryColor.withOpacity(0.12),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppConfig.primaryColor.withOpacity(0.3)),
                ),
                child: Text(
                  'Page ${_currentPageIndex + 1} of ${_edition!.pages.length}',
                  style: const TextStyle(
                    color: AppConfig.primaryColor,
                    fontWeight: FontWeight.w900,
                    fontSize: 12,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              // Page Title
              Expanded(
                child: Text(
                  _edition!.pages[_currentPageIndex].pageTitle,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                    color: isDark ? Colors.white70 : const Color(0xFF334155),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              // Zoom Controls
              IconButton(
                icon: const Icon(Icons.zoom_out, size: 20),
                tooltip: 'Zoom Out',
                padding: EdgeInsets.zero,
                constraints: const BoxConstraints(minWidth: 32, minHeight: 32),
                onPressed: () => _zoomOut(_currentPageIndex),
              ),
              IconButton(
                icon: const Icon(Icons.center_focus_strong, size: 20),
                tooltip: 'Reset Zoom (100%)',
                padding: EdgeInsets.zero,
                constraints: const BoxConstraints(minWidth: 32, minHeight: 32),
                onPressed: () => _resetZoom(_currentPageIndex),
              ),
              IconButton(
                icon: const Icon(Icons.zoom_in, size: 20),
                tooltip: 'Zoom In',
                padding: EdgeInsets.zero,
                constraints: const BoxConstraints(minWidth: 32, minHeight: 32),
                onPressed: () => _zoomIn(_currentPageIndex),
              ),
            ],
          ),
        ),

        // Interactive Page Viewer
        Expanded(
          child: PageView.builder(
            controller: _pageController,
            itemCount: _edition!.pages.length,
            onPageChanged: (index) {
              HapticFeedback.lightImpact();
              setState(() {
                _currentPageIndex = index;
              });
            },
            itemBuilder: (context, index) {
              final page = _edition!.pages[index];
              final zoomController = _getZoomController(index);

              return GestureDetector(
                onDoubleTap: () {
                  if (zoomController.value != Matrix4.identity()) {
                    zoomController.value = Matrix4.identity();
                  } else {
                    zoomController.value = Matrix4.identity()..scale(2.5);
                  }
                },
                child: Center(
                  child: InteractiveViewer(
                    transformationController: zoomController,
                    minScale: 1.0,
                    maxScale: 5.0,
                    clipBehavior: Clip.none,
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 10.0, vertical: 8.0),
                      child: Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(8),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(isDark ? 0.6 : 0.22),
                              blurRadius: 18,
                              offset: const Offset(0, 8),
                            ),
                          ],
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: CachedNetworkImage(
                          imageUrl: page.pageImage,
                          fit: BoxFit.contain,
                          placeholder: (context, url) => Container(
                            height: 400,
                            color: isDark ? const Color(0xFF1E293B) : Colors.white,
                            child: const Center(
                              child: CircularProgressIndicator(color: AppConfig.primaryColor),
                            ),
                          ),
                          errorWidget: (context, url, error) => Container(
                            height: 300,
                            color: isDark ? const Color(0xFF1E293B) : Colors.white,
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                const Icon(Icons.broken_image, size: 48, color: Colors.grey),
                                const SizedBox(height: 8),
                                Text(
                                  'Page ${page.pageNumber} could not be rendered',
                                  style: const TextStyle(fontSize: 12, color: Colors.grey),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildBottomBar(bool isDark) {
    return Container(
      height: 94,
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF1E293B) : Colors.white,
        border: Border(top: BorderSide(color: isDark ? const Color(0xFF334155) : const Color(0xFFE2E8F0))),
      ),
      child: Column(
        children: [
          // Thumbnails Strip
          Expanded(
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              itemCount: _edition!.pages.length,
              itemBuilder: (context, index) {
                final page = _edition!.pages[index];
                final isSelected = index == _currentPageIndex;

                return GestureDetector(
                  onTap: () {
                    _pageController.animateToPage(
                      index,
                      duration: const Duration(milliseconds: 300),
                      curve: Curves.easeInOut,
                    );
                  },
                  child: Container(
                    width: 58,
                    margin: const EdgeInsets.symmetric(horizontal: 4),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(6),
                      border: Border.all(
                        color: isSelected ? AppConfig.primaryColor : (isDark ? const Color(0xFF475569) : const Color(0xFFCBD5E1)),
                        width: isSelected ? 2.5 : 1.0,
                      ),
                      boxShadow: isSelected
                          ? [
                              BoxShadow(
                                color: AppConfig.primaryColor.withOpacity(0.35),
                                blurRadius: 6,
                                offset: const Offset(0, 2),
                              )
                            ]
                          : null,
                    ),
                    child: Stack(
                      fit: StackFit.expand,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(4),
                          child: CachedNetworkImage(
                            imageUrl: page.pageImage,
                            fit: BoxFit.cover,
                            placeholder: (_, __) => Container(color: Colors.grey[300]),
                            errorWidget: (_, __, ___) => Container(color: Colors.grey[400]),
                          ),
                        ),
                        // Page Number Badge
                        Positioned(
                          bottom: 0,
                          left: 0,
                          right: 0,
                          child: Container(
                            padding: const EdgeInsets.symmetric(vertical: 2),
                            color: isSelected ? AppConfig.primaryColor : Colors.black.withOpacity(0.7),
                            child: Text(
                              'P.${page.pageNumber}',
                              textAlign: TextAlign.center,
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 9,
                                fontWeight: FontWeight.w900,
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

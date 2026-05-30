# 123MOVIES Direct Importer & Auto-Generator Backend
# Zero-dependency Python 3 standard library script
# Run with: python import_helper.py

import os
import json
import re
import urllib.request
import socket
import subprocess
import threading
import queue
from http.server import HTTPServer, BaseHTTPRequestHandler
from socketserver import ThreadingMixIn

# Set default socket timeout to 10 seconds to prevent single-threaded hangs
socket.setdefaulttimeout(10)

PORT = 3000

# Multi-threaded HTTP Server — handles requests concurrently, never blocks
class ThreadingHTTPServer(ThreadingMixIn, HTTPServer):
    daemon_threads = True

# Shared state for background bulk_meta job
bulk_meta_lock = threading.Lock()
bulk_meta_status = {
    'running': False,
    'done': False,
    'updated': 0,
    'failed': 0,
    'total': 0,
    'error': None
}

# HTML template matching the exact website structure (john-wick-2.html style)
HTML_TEMPLATE = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch {title} ({year}) Full Movie Online Free - 123MOVIES</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Watch {title} ({year}) full movie online in HD. {overview}">
    <meta name="keywords" content="{title} {year}, watch {title} online, movie streaming, free movies, 123movies">
    <link rel="canonical" href="{slug}.html">

    <link rel="stylesheet" href="style.css?v=3.0">
    <style>
        /* Premium AdBlock UI */
        #adblockOverlay {{
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(5, 5, 5, 0.95) !important;
            backdrop-filter: blur(25px) saturate(180%) !important;
            z-index: 9999999 !important;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }}

        #adblockOverlay.active {{
            display: flex !important;
            opacity: 1 !important;
        }}

        .premium-popup {{
            background: rgba(20, 20, 20, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 40px !important;
            padding: 4rem 3rem !important;
            max-width: 550px !important;
            width: 90% !important;
            text-align: center !important;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8), 
                        0 0 40px rgba(46, 204, 113, 0.1) !important;
            position: relative;
            overflow: hidden;
            transform: translateY(30px) scale(0.95);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }}

        #adblockOverlay.active .premium-popup {{
            transform: translateY(0) scale(1) !important;
        }}

        .premium-popup::before {{
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(46, 204, 113, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }}

        .block-icon-container {{
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent-color), #27ae60);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            font-size: 3.5rem;
            box-shadow: 0 20px 40px rgba(46, 204, 113, 0.3);
            animation: float 4s ease-in-out infinite;
        }}

        @keyframes float {{
            0%, 100% {{ transform: translateY(0); }}
            50% {{ transform: translateY(-15px); }}
        }}

        .premium-popup h2 {{
            font-size: 2.8rem !important;
            font-weight: 900 !important;
            margin-bottom: 1.5rem !important;
            background: linear-gradient(to bottom, #fff, #aaa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }}

        .premium-popup p {{
            color: var(--text-secondary) !important;
            font-size: 1.2rem !important;
            line-height: 1.8 !important;
            margin-bottom: 3rem !important;
            padding: 0 1rem;
        }}

        .btn-modern-refresh {{
            background: #fff !important;
            color: #000 !important;
            border: none !important;
            padding: 1.2rem 3.5rem !important;
            border-radius: 100px !important;
            font-size: 1.1rem !important;
            font-weight: 900 !important;
            cursor: pointer !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2) !important;
        }}

        .btn-modern-refresh:hover {{
            transform: scale(1.05) translateY(-3px) !important;
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.3) !important;
            background: var(--accent-color) !important;
        }}

        .content-blur-premium {{
            filter: blur(20px) brightness(0.3) !important;
            pointer-events: none !important;
            transition: filter 1s ease !important;
        }}
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    
    <!-- Ad Script -->
    <script src="https://pl29338826.profitablecpmratenetwork.com/f6/4a/08/f64a08392f3fe1330ea21ff2b3933c5f.js"></script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.html'" style="cursor: pointer;">
                123M <div class="play-icon">▶</div> VIES
            </div>
            
            <div class="category-filter-container" id="categoryFilterContainer">
                <button class="category-dropdown-btn" id="categoryDropdownBtn">
                    <span>All Categories</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="category-dropdown-menu" id="categoryDropdownMenu">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search movies...">
                <button id="searchButton">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <div id="searchSuggestions" class="search-suggestions"></div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="player-layout">
            <!-- Left Ads Sidebar -->
            <aside class="ad-sidebar">
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
            </aside>

            <!-- Center Player Section -->
            <section class="main-player-section">
                
                <!-- TOP AD SLOT -->
                <div class="ad-container-horizontal">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : '0f1d8f34ec4871afaca3d58f406adb98', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/0f1d8f34ec4871afaca3d58f406adb98/invoke.js"></script>
                </div>

                <div class="video-container">
                    <iframe id="videoPlayer" src="https://vidcore.net/movie/{tmdb_id}?autoPlay=true&sub=en" allowfullscreen></iframe>
                </div>

                <!-- Server Switcher -->
                <div class="server-switcher" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;">
                    <button class="server-btn active" onclick="switchServer('https://vidcore.net/movie/{tmdb_id}?autoPlay=true&sub=en', this)">VidCore</button>
                    <button class="server-btn" onclick="switchServer('https://vidfast.pro/movie/{tmdb_id}?autoPlay=true&sub=en', this)">VidFast</button>
                    <button class="server-btn" onclick="switchServer('https://player.videasy.net/movie/{tmdb_id}?autoPlay=true&sub=en', this)">VidEasy</button>
                    <button class="server-btn" onclick="switchServer('https://vidlink.pro/movie/{tmdb_id}?autoPlay=true&sub=en', this)">VidLink</button>
                    <button class="server-btn" onclick="switchServer('https://vidsrc.stream/movie/{tmdb_id}?autoPlay=true&sub=en', this)">Vidsrc0</button>
                    <button class="server-btn" onclick="switchServer('https://multiembed.mov/?video_id={tmdb_id}&tmdb=1', this)">MultiEmbed</button>
                    <button class="server-btn" onclick="switchServer('https://www.2embed.cc/embed/{tmdb_id}', this)">2Embed</button>
                </div>

                <script>
                    function switchServer(url, btn) {{
                        document.getElementById('videoPlayer').src = url;
                        // Update active button
                        document.querySelectorAll('.server-btn').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                    }}
                </script>

                <!-- BOTTOM AD SLOT -->
                <div class="ad-container-horizontal" style="margin-top: 1.5rem;">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : '0f1d8f34ec4871afaca3d58f406adb98', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/0f1d8f34ec4871afaca3d58f406adb98/invoke.js"></script>
                </div>
                
                <div class="movie-details">
                    <h1 style="color: var(--accent-color); margin-bottom: 1.5rem;">{title} ({year})</h1>
                    <div class="movie-description">
                        <p>{overview}</p>
                    </div>
                </div>
            </section>

            <!-- Right Ads Sidebar -->
            <aside class="ad-sidebar">
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
            </aside>
        </div>
    </main>

    <!-- Adblocker Detection UI -->
    <div class="ad-zone-bait adsbox ad-placement ad-unit ad-zone"></div>
    <div id="adblockOverlay">
        <div class="premium-popup">
            <div class="block-icon-container">🚫</div>
            <h2>AdBlock Detected!</h2>
            <p>Please disable your adblocker to continue. We use a few ads to keep our movie library free for everyone.</p>
            <button class="btn-modern-refresh" id="refreshBtn">I've disabled it, Refresh</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {{
            const detect = () => {{
                const bait = document.querySelector('.ad-zone-bait');
                const overlay = document.getElementById('adblockOverlay');
                const main = document.querySelector('.container') || document.querySelector('.player-layout');
                if (bait && overlay) {{
                    const isBlocked = window.getComputedStyle(bait).display === 'none' || bait.offsetHeight === 0;
                    if (isBlocked) {{
                        overlay.classList.add('active');
                        if (main) main.classList.add('content-blur-premium');
                        document.body.style.overflow = 'hidden';
                    }}
                }}
            }};
            setTimeout(detect, 600);
            setTimeout(detect, 2000);
            document.getElementById('refreshBtn').addEventListener('click', () => window.location.reload());
        }});
    </script>
    <script src="main.js?v=3.0"></script>

    <!-- Popup/Ad Focus Guard: ads open in background, this page stays active -->
    <script>
        (function () {{
            var _origOpen = window.open;
            var _popupPending = false;
            var lastClickTime = 0;

            document.addEventListener('click', function () {{
                lastClickTime = Date.now();
            }}, true);

            window.open = function (url, target, features) {{
                _popupPending = true;
                var safeFeatures = (features || '') + ',noopener,noreferrer';
                var win = _origOpen.call(window, url, '_blank', safeFeatures);
                if (win) {{
                    try {{ win.blur(); }} catch (e) {{}}
                }}
                var focusInterval = setInterval(function () {{
                    try {{ window.focus(); }} catch (e) {{}}
                }}, 50);
                setTimeout(function () {{
                    clearInterval(focusInterval);
                    _popupPending = false;
                }}, 1000);
                return win;
            }};

            window.addEventListener('blur', function () {{
                if (_popupPending || (Date.now() - lastClickTime < 2000)) {{
                    var blurFocusInterval = setInterval(function () {{
                        try {{ window.focus(); }} catch (e) {{}}
                    }}, 50);
                    setTimeout(function () {{
                        clearInterval(blurFocusInterval);
                    }}, 1000);
                }}
            }});
        }})();
    </script>
</body>
</html>
"""

# TV Series template matching the exact website structure with Season & Episode Selector
TV_TEMPLATE = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch {title} ({year}) TV Series Online Free - 123MOVIES</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Watch {title} ({year}) TV series full episodes online in HD. {overview}">
    <meta name="keywords" content="{title} {year}, watch {title} online, TV show streaming, free TV episodes, 123movies">
    <link rel="canonical" href="{slug}.html">

    <link rel="stylesheet" href="style.css?v=3.1">
    <style>
        /* Premium AdBlock UI */
        #adblockOverlay {{
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(5, 5, 5, 0.95) !important;
            backdrop-filter: blur(25px) saturate(180%) !important;
            z-index: 9999999 !important;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }}

        #adblockOverlay.active {{
            display: flex !important;
            opacity: 1 !important;
        }}

        .premium-popup {{
            background: rgba(20, 20, 20, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 40px !important;
            padding: 4rem 3rem !important;
            max-width: 550px !important;
            width: 90% !important;
            text-align: center !important;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8), 
                        0 0 40px rgba(46, 204, 113, 0.1) !important;
            position: relative;
            overflow: hidden;
            transform: translateY(30px) scale(0.95);
            transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }}

        #adblockOverlay.active .premium-popup {{
            transform: translateY(0) scale(1) !important;
        }}

        .premium-popup::before {{
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(46, 204, 113, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }}

        .block-icon-container {{
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent-color), #27ae60);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2.5rem;
            font-size: 3.5rem;
            box-shadow: 0 20px 40px rgba(46, 204, 113, 0.3);
            animation: float 4s ease-in-out infinite;
        }}

        @keyframes float {{
            0%, 100% {{ transform: translateY(0); }}
            50% {{ transform: translateY(-15px); }}
        }}

        .premium-popup h2 {{
            font-size: 2.8rem !important;
            font-weight: 900 !important;
            margin-bottom: 1.5rem !important;
            background: linear-gradient(to bottom, #fff, #aaa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }}

        .premium-popup p {{
            color: var(--text-secondary) !important;
            font-size: 1.2rem !important;
            line-height: 1.8 !important;
            margin-bottom: 3rem !important;
            padding: 0 1rem;
        }}

        .btn-modern-refresh {{
            background: #fff !important;
            color: #000 !important;
            border: none !important;
            padding: 1.2rem 3.5rem !important;
            border-radius: 100px !important;
            font-size: 1.1rem !important;
            font-weight: 900 !important;
            cursor: pointer !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.2) !important;
        }}

        .btn-modern-refresh:hover {{
            transform: scale(1.05) translateY(-3px) !important;
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.3) !important;
            background: var(--accent-color) !important;
        }}

        .content-blur-premium {{
            filter: blur(20px) brightness(0.3) !important;
            pointer-events: none !important;
            transition: filter 1s ease !important;
        }}

        /* Seasons & Episodes Selector Styling */
        .season-tab {{
            background: rgba(255, 255, 255, 0.05);
            color: #aaa;
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.6rem 1.5rem;
            border-radius: 100px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
        }}
        .season-tab:hover {{
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }}
        .season-tab.active {{
            background: var(--accent-color);
            color: #000;
            font-weight: 800;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }}
        .episode-btn {{
            background: rgba(255, 255, 255, 0.03);
            color: #ccc;
            border: 1px solid rgba(255, 255, 255, 0.03);
            padding: 0.8rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }}
        .episode-btn:hover {{
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }}
        .episode-btn.active {{
            background: var(--accent-color);
            color: #000;
            font-weight: 800;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }}
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    
    <!-- Ad Script -->
    <script src="https://pl29338826.profitablecpmratenetwork.com/f6/4a/08/f64a08392f3fe1330ea21ff2b3933c5f.js"></script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo" onclick="window.location.href='index.html'" style="cursor: pointer;">
                123M <div class="play-icon">▶</div> VIES
            </div>
            
            <div class="category-filter-container" id="categoryFilterContainer">
                <button class="category-dropdown-btn" id="categoryDropdownBtn">
                    <span>All Categories</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="category-dropdown-menu" id="categoryDropdownMenu">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search movies...">
                <button id="searchButton">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <div id="searchSuggestions" class="search-suggestions"></div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="player-layout">
            <!-- Left Ads Sidebar -->
            <aside class="ad-sidebar">
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
            </aside>

            <!-- Center Player Section -->
            <section class="main-player-section">
                
                <!-- TOP AD SLOT -->
                <div class="ad-container-horizontal">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : '0f1d8f34ec4871afaca3d58f406adb98', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/0f1d8f34ec4871afaca3d58f406adb98/invoke.js"></script>
                </div>

                <div class="video-container">
                    <iframe id="videoPlayer" src="https://vidcore.net/tv/{tmdb_id}/1/1?autoPlay=true&sub=en" allowfullscreen></iframe>
                </div>

                <!-- Server Switcher -->
                <div class="server-switcher" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;">
                    <button class="server-btn active" data-template="https://vidcore.net/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en" onclick="switchServer('https://vidcore.net/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en', this)">VidCore</button>
                    <button class="server-btn" data-template="https://vidfast.pro/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en" onclick="switchServer('https://vidfast.pro/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en', this)">VidFast</button>
                    <button class="server-btn" data-template="https://player.videasy.net/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en" onclick="switchServer('https://player.videasy.net/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en', this)">VidEasy</button>
                    <button class="server-btn" data-template="https://vidlink.pro/tv/{{tmdb_id}}/{{season}}/{{episode}}" onclick="switchServer('https://vidlink.pro/tv/{{tmdb_id}}/{{season}}/{{episode}}', this)">VidLink</button>
                    <button class="server-btn" data-template="https://vidsrc.stream/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en" onclick="switchServer('https://vidsrc.stream/tv/{{tmdb_id}}/{{season}}/{{episode}}?autoPlay=true&sub=en', this)">Vidsrc0</button>
                    <button class="server-btn" data-template="https://multiembed.mov/?video_id={{tmdb_id}}&tmdb=1&s={{season}}&e={{episode}}" onclick="switchServer('https://multiembed.mov/?video_id={{tmdb_id}}&tmdb=1&s={{season}}&e={{episode}}', this)">MultiEmbed</button>
                    <button class="server-btn" data-template="https://www.2embed.cc/embedtv/{{tmdb_id}}&s={{season}}&e={{episode}}" onclick="switchServer('https://www.2embed.cc/embedtv/{{tmdb_id}}&s={{season}}&e={{episode}}', this)">2Embed</button>
                </div>

                <!-- Season & Episode Selection Grid -->
                <div class="tv-selector-container" style="margin-top: 1.5rem; background: rgba(20, 20, 20, 0.5); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 1.5rem;">
                    <div id="seasonWrapper" class="season-selector-wrapper" style="display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 1rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                        <!-- Season tabs generated here -->
                    </div>
                    <div id="episodeWrapper" class="episode-selector-wrapper" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem; margin-top: 1.5rem; max-height: 250px; overflow-y: auto; padding-right: 0.5rem;">
                        <!-- Episode buttons generated here -->
                    </div>
                </div>

                <!-- BOTTOM AD SLOT -->
                <div class="ad-container-horizontal" style="margin-top: 1.5rem;">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : '0f1d8f34ec4871afaca3d58f406adb98', 'format' : 'iframe', 'height' : 90, 'width' : 728, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/0f1d8f34ec4871afaca3d58f406adb98/invoke.js"></script>
                </div>
                
                <div class="movie-details">
                    <h1 style="color: var(--accent-color); margin-bottom: 1.5rem;">{title} ({year})</h1>
                    <div class="movie-description">
                        <p>{overview}</p>
                    </div>
                </div>
            </section>

            <!-- Right Ads Sidebar -->
            <aside class="ad-sidebar">
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
                <div class="ad-container">
                    <script type="text/javascript">
                        atOptions = {{ 'key' : 'b4d27efced0c20f7927b385e9171b458', 'format' : 'iframe', 'height' : 250, 'width' : 300, 'params' : {{}} }};
                    </script>
                    <script type="text/javascript" src="https://www.highperformanceformat.com/b4d27efced0c20f7927b385e9171b458/invoke.js"></script>
                </div>
            </aside>
        </div>
    </main>

    <!-- Adblocker Detection UI -->
    <div class="ad-zone-bait adsbox ad-placement ad-unit ad-zone"></div>
    <div id="adblockOverlay">
        <div class="premium-popup">
            <div class="block-icon-container">🚫</div>
            <h2>AdBlock Detected!</h2>
            <p>Please disable your adblocker to continue. We use a few ads to keep our movie library free for everyone.</p>
            <button class="btn-modern-refresh" id="refreshBtn">I've disabled it, Refresh</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {{
            const detect = () => {{
                const bait = document.querySelector('.ad-zone-bait');
                const overlay = document.getElementById('adblockOverlay');
                const main = document.querySelector('.container') || document.querySelector('.player-layout');
                if (bait && overlay) {{
                    const isBlocked = window.getComputedStyle(bait).display === 'none' || bait.offsetHeight === 0;
                    if (isBlocked) {{
                        overlay.classList.add('active');
                        if (main) main.classList.add('content-blur-premium');
                        document.body.style.overflow = 'hidden';
                    }}
                }}
            }};
            setTimeout(detect, 600);
            setTimeout(detect, 2000);
            document.getElementById('refreshBtn').addEventListener('click', () => window.location.reload());
        }});
    </script>
    <script src="main.js?v=3.1"></script>

    <!-- TV Episode Switcher Logic -->
    <script>
        const seasonsData = {seasons_data};
        let currentSeason = 1;
        let currentEpisode = 1;
        const tmdbId = "{tmdb_id}";

        function switchServer(baseUrl, btn) {{
            const activeBtn = document.querySelector('.server-btn.active');
            if (activeBtn) activeBtn.classList.remove('active');
            btn.classList.add('active');
            
            updatePlayer();
        }}

        function updatePlayer() {{
            const activeBtn = document.querySelector('.server-btn.active');
            let template = activeBtn.getAttribute('data-template');
            
            let playerUrl = template
                .replace('{{tmdb_id}}', tmdbId)
                .replace('{{season}}', currentSeason)
                .replace('{{episode}}', currentEpisode);

            document.getElementById('videoPlayer').src = playerUrl;
        }}

        function initSelectors() {{
            const seasonWrapper = document.getElementById('seasonWrapper');
            if (!seasonWrapper) return;
            
            seasonWrapper.innerHTML = '';
            seasonsData.forEach(s => {{
                if (s.season_number === 0) return; // Skip specials
                const tab = document.createElement('button');
                tab.className = 'season-tab' + (s.season_number === currentSeason ? ' active' : '');
                tab.textContent = 'Season ' + s.season_number;
                tab.addEventListener('click', () => {{
                    document.querySelectorAll('.season-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentSeason = s.season_number;
                    currentEpisode = 1;
                    renderEpisodes(s.episode_count);
                    updatePlayer();
                }});
                seasonWrapper.appendChild(tab);
            }});

            const initialSeason = seasonsData.find(s => s.season_number === currentSeason) || seasonsData[0];
            if (initialSeason) {{
                renderEpisodes(initialSeason.episode_count);
            }}
        }}

        function renderEpisodes(count) {{
            const episodeWrapper = document.getElementById('episodeWrapper');
            if (!episodeWrapper) return;
            
            episodeWrapper.innerHTML = '';
            for (let i = 1; i <= count; i++) {{
                const btn = document.createElement('button');
                btn.className = 'episode-btn' + (i === currentEpisode ? ' active' : '');
                btn.textContent = 'Ep ' + i;
                btn.addEventListener('click', () => {{
                    document.querySelectorAll('.episode-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentEpisode = i;
                    updatePlayer();
                }});
                episodeWrapper.appendChild(btn);
            }}
        }}

        document.addEventListener('DOMContentLoaded', () => {{
            initSelectors();
        }});
    </script>

    <!-- Popup/Ad Focus Guard: ads open in background, this page stays active -->
    <script>
        (function () {{
            var _origOpen = window.open;
            var _popupPending = false;
            var lastClickTime = 0;

            document.addEventListener('click', function () {{
                lastClickTime = Date.now();
            }}, true);

            window.open = function (url, target, features) {{
                _popupPending = true;
                var safeFeatures = (features || '') + ',noopener,noreferrer';
                var win = _origOpen.call(window, url, '_blank', safeFeatures);
                if (win) {{
                    try {{ win.blur(); }} catch (e) {{}}
                }}
                var focusInterval = setInterval(function () {{
                    try {{ window.focus(); }} catch (e) {{}}
                }}, 50);
                setTimeout(function () {{
                    clearInterval(focusInterval);
                    _popupPending = false;
                }}, 1000);
                return win;
            }};

            window.addEventListener('blur', function () {{
                if (_popupPending || (Date.now() - lastClickTime < 2000)) {{
                    var blurFocusInterval = setInterval(function () {{
                        try {{ window.focus(); }} catch (e) {{}}
                    }}, 50);
                    setTimeout(function () {{
                        clearInterval(blurFocusInterval);
                    }}, 1000);
                }}
            }});
        }})();
    </script>
</body>
</html>
"""

# Robust, thread-safe debounced deployment queue
deploy_lock = threading.Lock()
deploy_in_progress = False
deploy_queue = queue.Queue()
pending_files_lock = threading.Lock()
pending_files = set()

def deploy_worker():
    global deploy_in_progress
    import time
    while True:
        try:
            # Block until a deployment request is received
            item = deploy_queue.get()
            if item is None:
                break
            
            # Debounce: wait 3 seconds to let multiple consecutive imports bundle together
            time.sleep(3)
            # Clear any other requests currently in the queue
            while not deploy_queue.empty():
                try:
                    deploy_queue.get_nowait()
                    deploy_queue.task_done()
                except queue.Empty:
                    break
            
            # Get the accumulated pending files
            with pending_files_lock:
                files_to_upload = list(pending_files)
                pending_files.clear()
                
            if not files_to_upload:
                files_to_upload = ["main.js"]
            else:
                if "main.js" not in files_to_upload:
                    files_to_upload.append("main.js")
            
            with deploy_lock:
                deploy_in_progress = True
            
            try:
                print("\n" + "="*50)
                print(f"[*] TRIGGERING AUTO-DEPLOYMENT OF {len(files_to_upload)} FILES...")
                print(f"[*] Files: {files_to_upload}")
                print("="*50)
                
                # Join files into a single space-separated string so our robust deploy.ps1 split parser handles them perfectly
                files_arg = " ".join(files_to_upload)
                cmd = ["powershell", "-ExecutionPolicy", "Bypass", "-File", "deploy.ps1", "-Files", files_arg]
                result = subprocess.run(
                    cmd,
                    capture_output=True,
                    text=True,
                    timeout=120  # 2 minutes safety timeout
                )
                
                if result.returncode == 0:
                    print("[*] AUTO-DEPLOYMENT COMPLETED SUCCESSFULLY!")
                    if result.stdout:
                        print(result.stdout.strip())
                else:
                    print(f"[!] AUTO-DEPLOYMENT FAILED (code {result.returncode}):\n{result.stderr}")
                    if result.stdout:
                        print(result.stdout.strip())
                print("="*50 + "\n")
            except subprocess.TimeoutExpired:
                print("[!] AUTO-DEPLOYMENT HUNG AND WAS KILLED (Timeout expired after 120s)")
            except Exception as e:
                print(f"[!] Error executing auto-deployment: {e}\n")
            finally:
                with deploy_lock:
                    deploy_in_progress = False
                deploy_queue.task_done()
        except Exception as queue_err:
            print(f"[!] Worker exception: {queue_err}")

# Start the worker thread
worker_thread = threading.Thread(target=deploy_worker, daemon=True)
worker_thread.start()

def trigger_auto_deploy(changed_files=None):
    if changed_files:
        with pending_files_lock:
            for f in changed_files:
                pending_files.add(f)
    deploy_queue.put(True)

class RequestHandler(BaseHTTPRequestHandler):
    def end_headers(self):
        # Enable CORS for local developments and file:/// executions
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        super().end_headers()

    def do_OPTIONS(self):
        self.send_response(200, "OK")
        self.end_headers()

    def do_POST(self):
        print(f"\n[DEBUG] do_POST received path: {self.path}", flush=True)
        if self.path == '/import':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length)
            
            try:
                data = json.loads(post_data.decode('utf-8'))
                
                title = data.get('title')
                year = data.get('year')
                overview = data.get('overview', 'Watch in HD quality online. Stream now without any registration.')
                tmdb_id = data.get('tmdb_id')
                poster_path = data.get('poster_path')
                category = data.get('category')
                slug = data.get('slug')
                item_type = data.get('type', 'movie')
                seasons_data = data.get('seasons_data', [])

                if not all([title, year, tmdb_id, slug]):
                    self.send_error_response("Missing required parameters (title, year, tmdb_id, slug)")
                    return

                # 1. Resolve Remote Poster URL directly (No download)
                if poster_path:
                    image_url = f"https://image.tmdb.org/t/p/w500{poster_path}"
                else:
                    image_url = "assets/cover-placeholder.jpg" # local fallback
                print(f"Using remote hosted image URL: {image_url}")

                # Escape string fields for HTML and JS
                html_title = title.replace('"', '&quot;')
                html_overview = overview.replace('"', '&quot;')
                js_title = title.replace('"', '\\"')
                js_category = category.replace('"', '\\"') if category else ""

                # 2. Write HTML File
                if item_type == 'tv':
                    html_content = TV_TEMPLATE.format(
                        title=html_title,
                        year=year,
                        overview=html_overview,
                        tmdb_id=tmdb_id,
                        slug=slug,
                        seasons_data=json.dumps(seasons_data)
                    )
                    print(f"Generating TV series file with {len(seasons_data)} seasons...")
                else:
                    html_content = HTML_TEMPLATE.format(
                        title=html_title,
                        year=year,
                        overview=html_overview,
                        tmdb_id=tmdb_id,
                        slug=slug
                    )
                
                html_filename = f"{slug}.html"
                with open(html_filename, "w", encoding="utf-8") as f:
                    f.write(html_content)
                print(f"Generated file: {html_filename}")

                # 3. Modify main.js to add to MOVIE_DATABASE
                main_js_path = "main.js"
                if os.path.exists(main_js_path):
                    with open(main_js_path, "r", encoding="utf-8") as f:
                        main_js = f.read()

                    # Check if already exists in main.js
                    if f'url: "{slug}.html"' in main_js:
                        print(f"Database item {slug}.html already exists. Skipping database append.")
                    else:
                        db_regex = r"(/\*\s*DB_START\s*\*/)(.*?)(/\*\s*DB_END\s*\*/)"
                        match = re.search(db_regex, main_js, re.DOTALL)
                        
                        if match:
                            db_content = match.group(2)
                            
                            # Build the new JS object
                            new_item_js = f"""\n    ,\n    {{\n        title: "{js_title}",\n        url: "{slug}.html",\n        image: "{image_url}",\n        category: "{js_category}"\n    }}"""
                            
                            # Insert before the end anchor
                            updated_db_content = db_content.rstrip() + new_item_js + "\n    "
                            
                            updated_main_js = main_js.replace(
                                match.group(0),
                                f"/* DB_START */{updated_db_content}/* DB_END */"
                            )
                            
                            with open(main_js_path, "w", encoding="utf-8") as f:
                                f.write(updated_main_js)
                            print(f"Updated {main_js_path} successfully!")
                        else:
                            print("Warning: Could not find /* DB_START */ and /* DB_END */ anchors in main.js")

                self.send_success_response({
                    "success": True,
                    "message": f"Successfully imported '{title}'!",
                    "html_file": html_filename,
                    "poster_file": image_url
                })
                
                # Automatically trigger background deployment to the live host site
                trigger_auto_deploy(["main.js", html_filename])

            except Exception as e:
                self.send_error_response(f"Server Error during processing: {str(e)}")
        elif self.path == '/sitemap':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length)
            
            try:
                data = json.loads(post_data.decode('utf-8'))
                domain = data.get('domain', 'https://123movie.qzz.io').rstrip('/')
                frequency = data.get('frequency', 'weekly')
                priority = data.get('priority', '0.8')
                
                import datetime
                today_str = datetime.date.today().isoformat()
                
                if os.path.exists(main_js_path):
                    with open(main_js_path, "r", encoding="utf-8") as f:
                        main_js_content = f.read()
                    
                    url_regex = r'url:\s*"([^"]+)"'
                    slugs = re.findall(url_regex, main_js_content)
                    slugs = list(dict.fromkeys(slugs)) # deduplicate
                
                xml_entries = []
                # Homepage (canonical)
                homepage_lastmod = today_str
                if os.path.exists("index.html"):
                    mtime = os.path.getmtime("index.html")
                    homepage_lastmod = datetime.date.fromtimestamp(mtime).isoformat()
                    
                xml_entries.append(f"""  <url>
    <loc>{domain}/</loc>
    <lastmod>{homepage_lastmod}</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>""")
                
                # Each movie
                for slug in slugs:
                    if slug == "index.html" or slug == "admin.html":
                        continue
                    
                    lastmod = today_str
                    if os.path.exists(slug):
                        mtime = os.path.getmtime(slug)
                        lastmod = datetime.date.fromtimestamp(mtime).isoformat()
                        
                    xml_entries.append(f"""  <url>
    <loc>{domain}/{slug}</loc>
    <lastmod>{lastmod}</lastmod>
    <changefreq>{frequency}</changefreq>
    <priority>{priority}</priority>
  </url>""")
                
                entries_str = "\n".join(xml_entries)
                xml_content = f"""<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{entries_str}
</urlset>"""
                
                sitemap_filename = "sitemap.xml"
                with open(sitemap_filename, "w", encoding="utf-8") as f:
                    f.write(xml_content)
                
                print(f"Generated sitemap: {sitemap_filename} containing {len(xml_entries)} URLs!")
                
                self.send_success_response({
                    "success": True,
                    "urls_count": len(xml_entries),
                    "filename": sitemap_filename
                })
                
                trigger_auto_deploy([sitemap_filename])
                
            except Exception as e:
                self.send_error_response(f"Server Error during sitemap generation: {str(e)}")
        elif self.path == '/bulk_meta':
            content_length = int(self.headers['Content-Length'])
            post_data = self.rfile.read(content_length)
            
            try:
                data = json.loads(post_data.decode('utf-8'))
                desc_template = data.get('description_template')
                keys_template = data.get('keywords_template')
                should_deploy = data.get('deploy', 'yes')
                
                if not desc_template or not keys_template:
                    self.send_error_response("Missing templates (description_template, keywords_template)")
                    return

                # If a job is already running, reject
                with bulk_meta_lock:
                    if bulk_meta_status['running']:
                        self.send_success_response({
                            "success": False,
                            "error": "A bulk meta job is already running. Please wait."
                        })
                        return
                    bulk_meta_status['running'] = True
                    bulk_meta_status['updated'] = 0
                    bulk_meta_status['failed'] = 0
                    bulk_meta_status['total'] = 0
                    bulk_meta_status['done'] = False
                    bulk_meta_status['error'] = None

                # Immediately respond so the server stays free
                self.send_success_response({
                    "success": True,
                    "async": True,
                    "message": "Bulk meta generation started in background. Poll /bulk_meta_status for progress."
                })

                # Run the heavy work in a background thread
                def run_bulk_meta():
                    try:
                        node_script = """
const fs = require('fs');
const code = fs.readFileSync('main.js', 'utf8');
const startIdx = code.indexOf('const MOVIE_DATABASE = [');
const endIdx = code.indexOf('];', startIdx) + 2;
const dbCode = code.substring(startIdx, endIdx);
const sandboxFn = new Function(dbCode + '; return MOVIE_DATABASE;');
const db = sandboxFn();
console.log(JSON.stringify(db.filter(x => x !== undefined && x !== null)));
"""
                        print(f"[BULK_META] Parsing movie database via Node...", flush=True)
                        result = subprocess.run(
                            ["node", "-e", node_script],
                            capture_output=True, text=True, encoding="utf-8"
                        )
                        if result.returncode != 0:
                            raise Exception(f"Node parser failed: {result.stderr}")

                        movies = json.loads(result.stdout)
                        print(f"[BULK_META] Loaded {len(movies)} movies. Starting rewrite loop...", flush=True)

                        with bulk_meta_lock:
                            bulk_meta_status['total'] = len(movies)

                        desc_pattern = r'(<meta\s+name="description"\s+content=")([^"]*)("\s*/?>)'
                        key_pattern = r'(<meta\s+name="keywords"\s+content=")([^"]*)("\s*/?>)'

                        updated_count = 0
                        failed_count = 0
                        changed_files = []

                        for movie in movies:
                            title = movie.get('title')
                            url = movie.get('url')
                            category = movie.get('category') or ""

                            if not title or not url:
                                continue

                            if not os.path.exists(url):
                                failed_count += 1
                                continue

                            with open(url, "r", encoding="utf-8") as f:
                                html_content = f.read()

                            year = "2026"
                            year_match = re.search(r'\b(19\d{2}|20\d{2})\b', category)
                            if year_match:
                                year = year_match.group(1)

                            existing_overview = ""
                            desc_match = re.search(r'<meta\s+name="description"\s+content="([^"]+)"', html_content, re.IGNORECASE)
                            if desc_match:
                                full_content = desc_match.group(1)
                                prefix = f"Watch {title} ({year}) full movie online in HD. "
                                existing_overview = full_content[len(prefix):] if full_content.startswith(prefix) else full_content

                            if not existing_overview:
                                existing_overview = "Watch full movie in HD quality online. Stream now without any registration."

                            new_desc = desc_template.replace('{title}', title).replace('{year}', year).replace('{overview}', existing_overview)
                            new_keys = keys_template.replace('{title}', title).replace('{year}', year)
                            esc_desc = new_desc.replace('"', '&quot;')
                            esc_key = new_keys.replace('"', '&quot;')

                            if re.search(desc_pattern, html_content, re.IGNORECASE):
                                html_content = re.sub(desc_pattern, lambda m: m.group(1) + esc_desc + m.group(3), html_content, flags=re.IGNORECASE)
                            else:
                                if "<head>" in html_content:
                                    html_content = html_content.replace("<head>", f'<head>\n    <meta name="description" content="{esc_desc}">')

                            if re.search(key_pattern, html_content, re.IGNORECASE):
                                html_content = re.sub(key_pattern, lambda m: m.group(1) + esc_key + m.group(3), html_content, flags=re.IGNORECASE)
                            else:
                                if "<head>" in html_content:
                                    html_content = html_content.replace("<head>", f'<head>\n    <meta name="keywords" content="{esc_key}">')

                            with open(url, "w", encoding="utf-8") as f:
                                f.write(html_content)

                            updated_count += 1
                            changed_files.append(url)

                            if updated_count % 100 == 0:
                                print(f"[BULK_META] Progress: {updated_count}/{len(movies)} pages updated...", flush=True)
                                with bulk_meta_lock:
                                    bulk_meta_status['updated'] = updated_count
                                    bulk_meta_status['failed'] = failed_count

                        with bulk_meta_lock:
                            bulk_meta_status['updated'] = updated_count
                            bulk_meta_status['failed'] = failed_count
                            bulk_meta_status['done'] = True
                            bulk_meta_status['running'] = False

                        print(f"[BULK_META] Done! Updated {updated_count} pages, {failed_count} failed.", flush=True)

                        if should_deploy == "yes" and changed_files:
                            trigger_auto_deploy(changed_files)

                    except Exception as e:
                        print(f"[BULK_META] ERROR: {e}", flush=True)
                        with bulk_meta_lock:
                            bulk_meta_status['error'] = str(e)
                            bulk_meta_status['running'] = False
                            bulk_meta_status['done'] = True

                t = threading.Thread(target=run_bulk_meta, daemon=True)
                t.start()

            except Exception as e:
                self.send_error_response(f"Server Error during bulk meta generation: {str(e)}")

        elif self.path == '/bulk_meta_status':
            # Poll endpoint — returns live progress of running bulk meta job
            self.rfile.read(int(self.headers.get('Content-Length', 0)))
            with bulk_meta_lock:
                status_copy = dict(bulk_meta_status)
            self.send_success_response({
                "success": True,
                "running": status_copy['running'],
                "done": status_copy['done'],
                "updated": status_copy['updated'],
                "failed": status_copy['failed'],
                "total": status_copy['total'],
                "error": status_copy['error']
            })
        else:
            self.send_error_response("Endpoint not found", 404)

    def send_success_response(self, data):
        self.send_response(200)
        self.send_header('Content-Type', 'application/json')
        self.end_headers()
        self.wfile.write(json.dumps(data).encode('utf-8'))

    def send_error_response(self, message, code=400):
        self.send_response(code)
        self.send_header('Content-Type', 'application/json')
        self.end_headers()
        self.wfile.write(json.dumps({"success": False, "error": message}).encode('utf-8'))

def run_server():
    server_address = ('', PORT)
    httpd = ThreadingHTTPServer(server_address, RequestHandler)
    print(f"\n=======================================================")
    print(f"[*] 123MOVIES Importer Backend running on port {PORT}")
    print(f"[*] Direct local API URL: http://localhost:{PORT}/import")
    print(f"[*] Multi-threaded mode: ON (bulk ops won't block server)")
    print(f"=======================================================\n")
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nStopping importer server...")
        httpd.server_close()

if __name__ == '__main__':
    run_server()

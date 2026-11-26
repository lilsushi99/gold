#!/usr/bin/env python3
"""Generate Blade section templates + JSON definitions from the original template pages.
Run from repo root:  python3 tools/cms/build_pages.py
Outputs: resources/views/sections/*.blade.php, resources/cms/sections/*.json, database/content/pages.json"""
import json, re, sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).parent))
from slotify import slotify

ROOT = Path(__file__).resolve().parents[2]
SRC = ROOT / '1.Orisa HTML Template'
VIEWS = ROOT / 'resources/views/sections'
DEFS = ROOT / 'resources/cms/sections'
CONTENT = ROOT / 'database/content'

URL_MAP = {
    'index.html': '/', 'about-1.html': '/about', 'services-1.html': '/services', 'services-details.html': '/services/details',
    'portfolio-1.html': '/portfolio', 'portfolio-details-1.html': '/portfolio/details', 'archive-1.html': '/blog',
    'blog-details.html': '/blog/details', 'team.html': '/team', 'team-details.html': '/team/details',
    'pricing.html': '/pricing', 'faqs.html': '/faqs', 'contact-1.html': '/contact', '404.html': '/404',
}


def read(page):
    return (SRC / page).read_text(encoding='utf-8').replace('\r\n', '\n')


def between(src, start, end, nth=1, inclusive=False):
    i = -1
    for _ in range(nth):
        i = src.index(start, i + 1)
    j = src.index(end, i + len(start))
    return src[i:j + len(end)] if inclusive else src[i + len(start):j]


def write_section(stype, label, category, html, notes=''):
    blade, d = slotify(html, URL_MAP)
    VIEWS.mkdir(parents=True, exist_ok=True); DEFS.mkdir(parents=True, exist_ok=True)
    (VIEWS / f'{stype}.blade.php').write_text(blade + '\n', encoding='utf-8')
    d.update({'type': stype, 'label': label, 'category': category})
    (DEFS / f'{stype}.json').write_text(json.dumps(d, indent=1, ensure_ascii=False), encoding='utf-8')
    print(f'{stype:22s} slots={len(d["slots"]):4d} groups={len(d["groups"])} ({", ".join(str(len(g["items"])) for g in d["groups"])})')


def build_home():
    src = read('index.html')
    pages = {}
    # ---- global blocks (shared by every page) --------------------------------------------------------
    search = between(src, '<!--search-form-start -->', '<!-- search-form-end -->')
    header = between(src, '<header>', '</header>', inclusive=True)
    header = re.sub(r'(<nav class="at-mobile-menu-active">).*?(</nav>)', r'\1<x-cms-menu location="primary" />\2', header, flags=re.S)
    offcanvas = between(src, '<!-- at-offcanvus-area-start -->', '<!-- offcanvas 2 end -->')
    footer = between(src, '<footer class="container-2200">', '</footer>', inclusive=True)
    write_section('global_search', 'Search overlay', 'global', search)
    write_section('global_header', 'Header', 'global', header)
    write_section('global_offcanvas', 'Side menu panel', 'global', offcanvas)
    write_section('global_footer', 'Footer', 'global', footer)
    pages['_global'] = {'title': 'Global blocks (header, footer)', 'slug': '_global', 'template': 'global', 'is_system': True,
                        'sections': [('global_search', 'Search overlay'), ('global_header', 'Header'),
                                     ('global_offcanvas', 'Side menu panel'), ('global_footer', 'Footer')]}
    # ---- homepage sections ---------------------------------------------------------------------------
    S = [
        ('home_hero', 'Hero', '<!-- at-hero-area-start -->', '<!-- at-hero-area-end -->', 1),
        ('home_about', 'Introduction / Expertise', '<!-- at-about-area-start -->', '<!-- at-about-area-end -->', 1),
        ('home_brands', 'Client logos', '<!-- at-brand-area-start -->', '<!-- at-brand-area-end -->', 1),
        ('home_services', 'Services', '<!-- at-service-area-start -->', '<!-- at-service-area-end -->', 1),
        ('home_portfolio', 'Featured work', '<!-- mg-portfolio-area-start -->', '<!-- mg-portfolio-area-end -->', 1),
        ('home_testimonials', 'Testimonials', '<!--Home 1 Section 6-->', '<!-- at-sec7-area-start -->', 1),
        ('home_why', 'Why choose us', '<!-- at-sec7-area-start -->', '<!-- at-sec7-area-end -->', 1),
        ('home_stats', 'Statistics', '<!-- at-sec8-area-start -->', '<!-- at-sec8-area-end -->', 1),
        ('home_team', 'Team / We are here', '<!-- at-sec9-area-start -->', '<!-- at-sec9-area-end -->', 1),
        ('home_brands2', 'Client logos (second row)', '<!-- at-brand-area-start -->', '<!-- at-brand-area-end -->', 2),
        ('home_faq', 'FAQ', '<!-- alt-faq-area-start -->', '<!-- alt-faq-area-end -->', 1),
        ('home_cta', 'Call-to-action banner', '<!-- at-banner-area-start -->', '<!-- at-banner-area-end -->', 1),
        ('home_posts', 'Latest posts', '<!-- at-sec13-area-start -->', '<!-- at-sec13-area-end -->', 1),
    ]
    secs = []
    for stype, label, a, b, nth in S:
        html = between(src, a, b, nth)
        if stype == 'home_testimonials':
            html = src[src.index(a) + len(a):src.index(b)]
        write_section(stype, label, 'home', html)
        secs.append((stype, label))
    pages['home'] = {'title': 'Home', 'slug': 'home', 'template': 'default', 'is_home': True, 'is_system': True, 'sections': secs,
                     'seo_title': 'Orisa - Creative Agency', 'meta_description': ''}
    return pages


if __name__ == '__main__':
    pages = build_home()
    CONTENT.mkdir(parents=True, exist_ok=True)
    (CONTENT / 'pages.json').write_text(json.dumps(pages, indent=1, ensure_ascii=False), encoding='utf-8')
    print('pages.json written')

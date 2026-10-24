"""Tiny builder for Elementor page JSON (containers + classic widgets).

Every helper returns a plain dict in Elementor's `_elementor_data` format, so pages are
generated from code but stay 100% editable in the Elementor editor afterwards.
"""
import json
import random

_rng = random.Random(2026)
_used = set()
MEDIA = {}


def load_media(path):
    MEDIA.update(json.load(open(path)))


def rid():
    while True:
        i = ''.join(_rng.choice('0123456789abcdef') for _ in range(7))
        if i not in _used:
            _used.add(i)
            return i


def img(key):
    mid, url = MEDIA[key]
    return {'id': mid, 'url': url, 'size': '', 'alt': '', 'source': 'library'}


def dims(top=0, right=None, bottom=None, left=None, unit='px'):
    right = top if right is None else right
    bottom = top if bottom is None else bottom
    left = right if left is None else left
    return {'unit': unit, 'top': str(top), 'right': str(right), 'bottom': str(bottom), 'left': str(left), 'isLinked': False}


def _cls(*parts):
    return ' '.join(p for p in parts if p)


def container(children, cls='', row=False, tag='div', justify=None, align=None, gap=None, wrap=None, **extra):
    s = {'content_width': 'full', 'css_classes': _cls('ki-c', cls), 'flex_direction': 'row' if row else 'column'}
    if tag != 'div':
        s['html_tag'] = tag
    if justify:
        s['flex_justify_content'] = justify
    if align:
        s['flex_align_items'] = align
    if wrap:
        s['flex_wrap'] = wrap
    if gap is not None:
        s['flex_gap'] = {'column': str(gap), 'row': str(gap), 'isLinked': True, 'unit': 'px'}
    s.update(extra)
    return {'id': rid(), 'elType': 'container', 'settings': s, 'elements': children, 'isInner': False}


def widget(wtype, settings, cls=''):
    s = dict(settings)
    if cls:
        s['_css_classes'] = cls  # widgets use the underscored key; containers use css_classes
    return {'id': rid(), 'elType': 'widget', 'widgetType': wtype, 'settings': s, 'elements': []}


def heading(text, tag='h2', cls=''):
    return widget('heading', {'title': text, 'header_size': tag}, cls)


def text(html, cls=''):
    if not html.lstrip().startswith('<'):
        html = '<p>%s</p>' % html
    return widget('text-editor', {'editor': html}, cls)


def button(label, url='#', cls='', icon=None, new_tab=False):
    s = {'text': label, 'link': {'url': url, 'is_external': 'on' if new_tab else '', 'nofollow': '', 'custom_attributes': ''}}
    if icon:
        s['selected_icon'] = {'value': icon, 'library': 'fa-solid'}
        s['icon_align'] = 'right'
    return widget('button', s, cls)


def image(key, size='large', cls='', alt=''):
    i = img(key)
    return widget('image', {'image': i, 'image_size': size, 'image_custom_dimension': {'width': '', 'height': ''}}, cls)


def icon(name, cls='', library='fa-solid'):
    return widget('icon', {'selected_icon': {'value': name, 'library': library}, 'view': 'default'}, cls)


def icon_list(items, cls='', icon_name=None):
    rows = []
    for t in items:
        row = {'text': t, '_id': rid()}
        if icon_name:
            row['selected_icon'] = {'value': icon_name, 'library': 'fa-solid'}
        rows.append(row)
    s = {'icon_list': rows}
    return widget('icon-list', s, cls)


def html(code, cls=''):
    return widget('html', {'html': code}, cls)


def shortcode(sc, cls=''):
    return widget('shortcode', {'shortcode': sc}, cls)


def maps(address, height=440, zoom=15, cls=''):
    return widget('google_maps', {'address': address, 'zoom': {'unit': 'px', 'size': zoom}, 'height': {'unit': 'px', 'size': height}}, cls)


# ---- Reusable blocks ---------------------------------------------------------

def section(children, kind='', cls='', first=False, tag='section'):
    """Full-width band with the standard centred inner container."""
    inner = container(children, 'container')
    return container([inner], _cls('section', kind, 'section--first' if first else '', cls), tag=tag)


def head(eyebrow, title, lead='', center=True, light=False, cls=''):
    kids = [heading(eyebrow, 'span', 'eyebrow' + (' eyebrow--light' if light else '')), heading(title, 'h2', 'h2')]
    if lead:
        kids.append(text(lead, 'lead'))
    return container(kids, _cls('section-head', 'center' if center else '', 'rv', cls))


def page_hero(eyebrow, title, lead, bg_key):
    decor = html('<span class="shape shape-a" aria-hidden="true"></span><span class="shape shape-b" aria-hidden="true"></span><span class="page-hero-shade" aria-hidden="true"></span>', 'hero-decor')
    inner = container([
        shortcode('[ki_breadcrumbs]', 'crumbs-wrap'),
        heading(eyebrow, 'span', 'eyebrow eyebrow--light'),
        heading(title, 'h1', 'page-title split'),
        text(lead, 'page-lead'),
    ], 'container page-hero-inner')
    bg = img(bg_key)
    return container([decor, inner], 'page-hero ki-hero', tag='section',
                     background_background='classic', background_image=bg, background_position='center center',
                     background_size='cover', background_repeat='no-repeat')


def cta_band(title, text_, eyebrow='Get a Quotation'):
    decor = html('<span class="cta-band-bg" aria-hidden="true"></span>', 'hero-decor')
    left = container([heading(eyebrow, 'span', 'eyebrow eyebrow--dark'), heading(title, 'h2'), text(text_)], 'cta-copy')
    actions = container([
        button('Request a quotation', '/contact-us/#quote', 'btn-dark btn-lg ki-quote magnetic', icon='fas fa-arrow-right'),
        shortcode('[ki_wa_button]', 'wa-btn-wrap'),
    ], 'cta-band-actions')
    return container([decor, container([left, actions], 'container cta-band-inner')], 'cta-band rv', tag='section')


def page(elements):
    return elements

"""Header and footer templates for the Elementor Theme Builder."""
from elib import *


def header():
    topbar = container([container([shortcode('[ki_topbar]')], 'container')], 'topbar')
    brand = container([
        image('logo-light-h', 'full', 'brand-light', link='/'),
        image('logo-h', 'full', 'brand-dark', link='/'),
    ], 'brand')
    actions = container([
        button('Get a Quotation', '/contact-us/#quote', 'header-cta btn-primary ki-quote magnetic'),
        shortcode('[ki_nav_toggle]', 'nav-toggle-wrap'),
    ], 'header-actions', row=True)
    bar = container([container([brand, shortcode('[ki_nav]', 'nav-wrap'), actions], 'container header-inner', row=True)], 'site-header')
    return [topbar, bar]


def footer():
    glow = html('<span class="footer-glow" aria-hidden="true"></span>', 'hero-decor')
    brand = container([
        image('logo-light-h', 'full', 'footer-logo', link='/'),
        text('High-quality, cutting-edge offset printing and packaging solutions. Commitment, dedication and customer satisfaction are the heart of how we work.', 'footer-about'),
        shortcode('[ki_social]'),
    ], 'footer-brand')
    explore = container([heading('Explore', 'h3'), icon_list([
        ('Home', '/'), ('About Us', '/about-us/'), ('Printing Services', '/printing-services/'), ('Packaging Solutions', '/packaging-solutions/'),
        ('Our Products', '/our-products/'), ('Gallery', '/gallery/'), ('Contact Us', '/contact-us/')], 'footer-links')], 'footer-col')
    make = container([heading('What we make', 'h3'), icon_list([
        ('Brochures & Catalogues', '/product-category/brochures-catalogues/'), ('Calendars & Cards', '/product-category/calendars-cards/'),
        ('Carry Bags', '/product-category/carry-bags/'), ('Cartons & Boxes', '/product-category/cartons-boxes/'),
        ('Corporate Stationery', '/product-category/corporate-stationery/'), ('Garment Accessories', '/product-category/garment-accessories/'),
        ('Labels & Stickers', '/product-category/labels-stickers/'), ('Posters & Advertising', '/product-category/posters-advertising/')], 'footer-links')], 'footer-col')
    contact = container([heading('Visit & contact', 'h3'), shortcode('[ki_footer_contact]'),
                         button('Get a Quotation', '/contact-us/#quote', 'btn-primary ki-quote')], 'footer-col footer-contact')
    grid = container([brand, explore, make, contact], 'container footer-grid')
    bottom = container([container([
        text('&copy; [ki_year] K I Packaging Solutions. All rights reserved.'),
        text('Offset Printing &amp; Packaging Solutions &middot; Kadawatha, Sri Lanka', 'footer-credit'),
    ], 'container footer-bottom-inner', row=True)], 'footer-bottom')
    return [container([glow, grid, bottom], 'site-footer')]

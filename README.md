# K I Packaging Solutions: WordPress site

Hand-coded WordPress build for https://kipackaging.esupportdev2.xyz/ (no page builder).

| Path | What it is |
| --- | --- |
| `wp-content/themes/ki-packaging` | Pure PHP theme: home, About Us, Printing Services, Packaging Solutions, Our Products (WooCommerce), Gallery, Contact Us. Self-hosted fonts, vanilla JS animations, responsive. |
| `wp-content/plugins/ki-packaging-core` | Custom plugin: quotation request system (form, spam protection, admin inbox, emails), gallery post type, business settings, SMTP, WhatsApp link. |
| `provision/` | Scripts used to create pages, menu, WooCommerce categories/products, gallery and to import media. Run through the Novamira `execute-php` ability. |
| `provision/elementor/` | Generator (`elib.py`, `pages.py`, `chrome_pages.py`) and importers (`import.php`, `import_chrome.php`) that produce the Elementor pages, header and footer. |
| `provision/ki-memory.php` | Must-use plugin that lifts PHP memory to 256M; Elementor exhausts the host's 128M default. |

## Brand
Orange `#F58220` and black `#0F0F12` taken from the logo. Headings Outfit, body Inter (both self-hosted).

## Things the client must supply (Settings > KI Packaging)
- Phone, public email, WhatsApp number (the chat button stays hidden until set), opening hours, social links.
- **SMTP mailbox details.** The host disables PHP `mail()`, so quotation emails are only sent once SMTP is configured. Requests are always saved under *Quotations* in wp-admin.

## Notes
- Product prices and the cart are disabled on purpose: WooCommerce works as a catalogue and every product leads to a quotation request.
- Product/page photos are from Pexels (free licence, no attribution required) plus images from the company profile.

## Editing with Elementor
All seven pages (Home, About Us, Printing Services, Packaging Solutions, Our Products banner, Gallery, Contact Us) are
native Elementor pages: containers, headings, text, buttons, images, icon lists. Edit them in the normal Elementor editor.

- The brand colours and fonts are set in the Elementor *Site Settings* (kit); the theme CSS (`assets/css/elementor.css`) styles the sections through class names (`ki-hero`, `section--dark`, `rv`, ...). Reuse an existing section as a starting point when adding new ones.
- Reveal animations: add the class `rv` (fade up), `rv-left` or `rv-img` to any container/widget. Count-up numbers: give a heading the class `ki-count`. Open the quotation popup from a button: class `ki-quote`.
- Data-driven blocks are shortcodes: `[ki_products]`, `[ki_gallery]`, `[ki_contact_info]`, `[ki_quote_form]`, `[ki_wa_button]`, `[ki_breadcrumbs]`.
- The **header and footer are Elementor Theme Builder templates** (Templates > Theme Builder: "KI Header", "KI Footer", shown site-wide). Logo, button, columns, links and text are normal widgets. Three small shortcodes stay data-driven: `[ki_nav]` (the menu, edited in Appearance > Menus), `[ki_topbar]` and `[ki_footer_contact]` / `[ki_social]` (from Settings > KI Packaging).
- The header keeps its behaviour through the `site-header` class: transparent over a hero (a section with the class `ki-hero`), solid on scroll, full-screen mobile menu. New pages that do not start with a `ki-hero` section automatically get the solid header.
- Products and the shop grid stay WooCommerce-native (WooCommerce > Products); the shop page banner is an Elementor page.
- If the Theme Builder templates are removed, `header.php` / `footer.php` render the same chrome from the same functions (`inc/chrome.php`).
- The original hand-coded PHP page templates are still in the theme (`page-*.php`, `front-page.php`) and are used automatically if a page is ever switched back from Elementor.

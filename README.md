# K I Packaging Solutions: WordPress site

Hand-coded WordPress build for https://kipackaging.esupportdev2.xyz/ (no page builder).

| Path | What it is |
| --- | --- |
| `wp-content/themes/ki-packaging` | Pure PHP theme: home, About Us, Printing Services, Packaging Solutions, Our Products (WooCommerce), Gallery, Contact Us. Self-hosted fonts, vanilla JS animations, responsive. |
| `wp-content/plugins/ki-packaging-core` | Custom plugin: quotation request system (form, spam protection, admin inbox, emails), gallery post type, business settings, SMTP, WhatsApp link. |
| `provision/` | Scripts used to create pages, menu, WooCommerce categories/products, gallery and to import media. Run through the Novamira `execute-php` ability. |

## Brand
Orange `#F58220` and black `#0F0F12` taken from the logo. Headings Outfit, body Inter (both self-hosted).

## Things the client must supply (Settings > KI Packaging)
- Phone, public email, WhatsApp number (the chat button stays hidden until set), opening hours, social links.
- **SMTP mailbox details.** The host disables PHP `mail()`, so quotation emails are only sent once SMTP is configured. Requests are always saved under *Quotations* in wp-admin.

## Notes
- Product prices and the cart are disabled on purpose: WooCommerce works as a catalogue and every product leads to a quotation request.
- Product/page photos are from Pexels (free licence, no attribution required) plus images from the company profile.

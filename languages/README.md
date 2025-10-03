# Janeth Salon Theme Translations

This directory contains translation files for the Janeth Salon Theme.

## Files Structure

- `janeth-salon-theme.pot` - Template file containing all translatable strings
- `es_ES.po` - Spanish (Spain) translation source file
- `es_ES.mo` - Spanish (Spain) compiled translation file (used by WordPress)

## How to Add New Languages

1. Copy the `janeth-salon-theme.pot` file and rename it to your language code (e.g., `fr_FR.po` for French)
2. Translate all the `msgstr ""` entries in the new .po file
3. Compile the .po file to .mo using the command:
   ```bash
   msgfmt -o [language_code].mo [language_code].po
   ```

## Common Language Codes

- `es_ES` - Spanish (Spain)
- `es_MX` - Spanish (Mexico)
- `fr_FR` - French (France)
- `de_DE` - German (Germany)
- `it_IT` - Italian (Italy)
- `pt_BR` - Portuguese (Brazil)
- `pt_PT` - Portuguese (Portugal)
- `ja` - Japanese
- `zh_CN` - Chinese (Simplified)
- `zh_TW` - Chinese (Traditional)
- `ru_RU` - Russian
- `ar` - Arabic

## Updating Translations

When you add new translatable strings to your theme:

1. Extract all translatable strings to update the .pot file:
   ```bash
   wp i18n make-pot /path/to/theme languages/janeth-salon-theme.pot
   ```
   
2. Update existing .po files with new strings:
   ```bash
   msgmerge -U es_ES.po janeth-salon-theme.pot
   ```

3. Translate the new strings in the .po file

4. Recompile the .mo file:
   ```bash
   msgfmt -o es_ES.mo es_ES.po
   ```

## Theme Integration

The theme is already configured to load translations with this line in `includes/setup-theme.php`:

```php
load_theme_textdomain( 'janeth-salon-theme', get_template_directory() . '/languages' );
```

## Testing Translations

1. Install and activate the theme
2. Go to WordPress Admin → Settings → General
3. Change "Site Language" to your translated language
4. View the frontend to see the translations in action

## Found Translatable Strings

The following strings have been identified and included in the translation files:

### Admin/Backend Strings
- Header Image
- Select Header Image
- Remove Image
- Upload a custom header image for this category.
- Left Featured Image
- Button Style
- Janeth Salon
- Filled Square

### Frontend Content
- Post Categories
- Hair Care
- Nails Care
- Testimonials
- Our Mission
- Contact Us
- Get in touch
- You might also like
- Latest Posts
- Search
- Archive
- Categories
- Recent Posts
- Tags
- Social Links
- Search the website
- Useful Links
- Follow Us

### Form Elements
- Name
- Email
- Website
- Comment
- Search site...
- Search

### Error Messages
- 404-Error Page
- Sorry, nothing was found for that search term.

### Content Blocks
- We are a non-government organization committed to sustainability and nature preservation...
- Links I found useful and wanted to share.
- Full site editing in WordPress
- Classic themes vs block themes
- Janeth Salon theme documentation

All these strings are now ready for translation in any language you need.
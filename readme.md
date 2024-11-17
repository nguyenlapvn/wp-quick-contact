# WP Quick Contact

WP Quick Contact is a WordPress plugin that adds floating contact buttons to your website. It provides a quick and easy way for visitors to reach you through various communication channels like Zalo, Messenger, WhatsApp, Telegram, phone calls, and more.

## Features

- 📱 Floating contact buttons that stick to the bottom right corner
- 🎨 Clean and modern design with smooth animations
- 📍 Multiple pre-configured social platforms:
  - Messenger
  - Zalo
  - Telegram
  - WhatsApp
  - Viber
  - Line
  - Discord
  - Phone calls
- ✨ Custom buttons with SVG icons support
- 📱 Fully responsive on all devices
- 🎯 Easy drag & drop ordering
- 🌐 Translation ready
- ⚡ Lightweight and fast

## Installation

1. Upload the `wp-quick-contact` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Quick Contact to configure your contact buttons

## Usage

### Adding Contact Buttons

1. Navigate to Settings > Quick Contact in your WordPress admin panel
2. Click "Add New Button"
3. Configure your button:
   - Enter the button name
   - Select the button type
   - Enter the URL/phone number
   - For custom buttons, paste your SVG icon code
4. Save changes

### Button Types and URL Formats

- **Messenger**: `https://m.me/your.page.id`
- **Zalo**: Your Zalo link
- **Telegram**: `https://t.me/yourusername`
- **WhatsApp**: `https://wa.me/yourphonenumber`
- **Viber**: Your Viber link
- **Line**: Your Line link
- **Discord**: Your Discord invite link
- **Phone**: Your phone number
- **Custom**: Any URL with custom SVG icon

## Customization

### Adding Custom Buttons

You can add custom buttons with your own SVG icons. The SVG should be 24x24 pixels and use the `viewBox="0 0 24 24"` attribute.

Example custom SVG:
```xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <path d="Your SVG path here"/>
</svg>
```

### CSS Customization

The plugin uses BEM-style class names for easy styling. Main classes:

- `.wpqc-container`: Main container
- `.wpqc-toggle`: Toggle button
- `.wpqc-buttons`: Buttons container
- `.wpqc-button`: Individual button
- `.wpqc-button-label`: Button label

## FAQ

**Q: Can I add multiple buttons of the same type?**  
A: Yes, you can add multiple buttons of the same type with different names and URLs.

**Q: How do I change the order of buttons?**  
A: Simply drag and drop the buttons in the admin panel to reorder them.

**Q: Can I customize the appearance?**  
A: Yes, you can customize the appearance using CSS. The plugin uses clear class names for easy styling.

## Requirements

- WordPress 6.5 or higher
- PHP 7.4 or higher

## Changelog

### 1.0.0
- Initial release

## Credits

- Icons by Fontawesome
- Developed by Nguyen Lap

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Support

For support, please use the [WordPress support forums](https://wordpress.org/support/plugin/wp-quick-contact/).

For bug reports or feature requests, please visit our [GitHub repository](https://github.com/nguyenlapvn/wp-quick-contact).
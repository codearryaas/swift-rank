# Schema Engine Blocks - Usage Guide

## Overview

Schema Engine now includes two powerful Gutenberg blocks that automatically generate structured data (schema markup) for your content:

1. **FAQ Block** - For Frequently Asked Questions with FAQPage schema
2. **HowTo Block** - For step-by-step instructions with HowTo schema

## Building the Blocks

Before using the blocks, you need to build them:

```bash
# Install dependencies (if not already done)
npm install

# Development build with watch mode
npm run start

# Production build
npm run build
```

The blocks will be built to:
- `build/blocks/faq/index.js`
- `build/blocks/howto/index.js`

## FAQ Block

### Features

- Add multiple question/answer pairs
- Rich text editing for questions and answers
- Automatic FAQPage schema generation
- Toggle to enable/disable schema
- Clean, intuitive interface

### How to Use

1. In the block editor, click the **+** button
2. Search for "FAQ" or find it under **Widgets**
3. Add the FAQ block to your page
4. Click on each question/answer to edit:
   - **Question**: Enter your question (supports bold/italic)
   - **Answer**: Enter the answer (supports bold, italic, links)
5. Click **Add FAQ Item** to add more questions
6. Remove items using the trash icon

### Settings

Access the block settings in the sidebar:

- **Enable FAQ Schema**: Toggle schema markup on/off (default: ON)

### Schema Output

When enabled, the block automatically generates FAQPage schema:

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What is Schema Engine?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Schema Engine is a WordPress plugin..."
      }
    }
  ]
}
```

### Example

**Question:** What is Schema Engine?
**Answer:** Schema Engine is a WordPress plugin that helps you add structured data to your website for better SEO.

**Question:** How do I use the FAQ block?
**Answer:** Simply add the FAQ block to your page and enter your questions and answers.

## HowTo Block

### Features

- Add multiple steps with instructions
- Optional images for each step
- Title and description for your guide
- Total time field
- Rich text editing
- Automatic HowTo schema generation
- Toggle to enable/disable schema

### How to Use

1. In the block editor, click the **+** button
2. Search for "How-To" or find it under **Widgets**
3. Add the HowTo block to your page
4. Fill in the main information:
   - **Title**: Enter the main title of your guide
   - **Description**: Add a brief description
5. For each step:
   - **Step Name**: Give the step a title
   - **Instructions**: Add detailed instructions
   - **Image (Optional)**: Add a visual for the step
6. Click **Add Step** to add more steps
7. Remove steps using the trash icon

### Settings

Access the block settings in the sidebar:

- **Enable HowTo Schema**: Toggle schema markup on/off (default: ON)
- **Total Time**: Enter duration in ISO 8601 format (e.g., "PT30M" for 30 minutes)

### Time Format Examples

- `PT30M` - 30 minutes
- `PT1H` - 1 hour
- `PT2H30M` - 2 hours 30 minutes
- `PT45S` - 45 seconds
- `P1D` - 1 day

### Schema Output

When enabled, the block automatically generates HowTo schema:

```json
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "How to Make Coffee",
  "description": "Learn to make perfect coffee",
  "totalTime": "PT15M",
  "step": [
    {
      "@type": "HowToStep",
      "position": 1,
      "name": "Boil Water",
      "itemListElement": {
        "@type": "HowToDirection",
        "text": "Bring water to a boil in a kettle."
      },
      "image": "https://example.com/boiling-water.jpg"
    }
  ]
}
```

### Example

**Title:** How to Make Coffee
**Description:** Learn to make the perfect cup of coffee at home.
**Total Time:** PT15M

**Steps:**

1. **Boil Water**
   Bring fresh water to a boil in a kettle. Use filtered water for best results.

2. **Grind Coffee**
   Grind coffee beans to a medium-coarse consistency.

3. **Brew Coffee**
   Pour hot water over grounds and let steep for 4 minutes.

## Block Customization

### Styling

Both blocks come with basic styling. You can customize the appearance:

**Frontend CSS** (add to your theme):

```css
/* FAQ Block Styling */
.schema-engine-faq-block {
  margin: 2rem 0;
}

.schema-engine-faq-item {
  border: 1px solid #ddd;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
}

.faq-question {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.faq-answer {
  color: #555;
  line-height: 1.6;
}

/* HowTo Block Styling */
.schema-engine-howto-block {
  margin: 2rem 0;
}

.howto-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

.schema-engine-howto-step {
  border-left: 4px solid #2271b1;
  padding-left: 1rem;
  margin-bottom: 1.5rem;
}

.step-number {
  background: #2271b1;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 600;
}

.step-name {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0.5rem 0;
}

.step-image img {
  max-width: 100%;
  height: auto;
  border-radius: 4px;
  margin-top: 1rem;
}
```

## SEO Benefits

### FAQ Block

- Helps appear in Google's FAQ rich results
- Improves content organization
- Increases click-through rates
- Better user experience
- Voice search optimization

### HowTo Block

- Eligible for Google's HowTo rich results
- Can show step-by-step carousel in search
- Increases visibility in search results
- Better content structure
- Enhanced mobile experience

## Testing Schema

After adding blocks, test your schema:

1. Publish your page
2. Use [Google Rich Results Test](https://search.google.com/test/rich-results)
3. Enter your page URL
4. Check for errors or warnings
5. Preview how it appears in search results

## Troubleshooting

### Schema Not Showing

1. Check that **Enable Schema** is turned ON in block settings
2. Make sure you've filled in all required fields
3. Publish the page (schema doesn't show in preview)
4. Clear your site cache

### Build Errors

```bash
# Clean and rebuild
rm -rf node_modules build
npm install
npm run build
```

### Blocks Not Appearing

1. Make sure you've run `npm run build`
2. Check that build files exist in `build/blocks/`
3. Clear WordPress cache
4. Refresh the editor page

## Best Practices

### FAQ Block

- Keep questions concise and clear
- Provide complete, helpful answers
- Use natural language (how people search)
- Group related questions together
- Aim for 5-10 FAQ items per block

### HowTo Block

- Use clear, actionable step names
- Break complex tasks into simple steps
- Add images to visual steps
- Include time estimates
- Test your instructions
- Use 3-10 steps (not too many)

## Advanced Usage

### Multiple Blocks

You can use multiple FAQ or HowTo blocks on the same page:

- Each block generates its own schema
- Schema items are combined automatically
- Keep blocks focused on specific topics

### Disable Schema

You might want to disable schema if:

- You're using another schema plugin
- The content is for design/layout only
- You want to add schema manually

## Support

For issues or questions:

1. Check [IMPLEMENTATION.md](IMPLEMENTATION.md) for technical details
2. Review the WordPress debug log
3. Test in a staging environment first
4. Check browser console for JavaScript errors

---

**Last Updated:** 2025-11-29
**Version:** 1.0.0

# FAQ Schema Setup Guide

FAQ (Frequently Asked Questions) schema helps search engines understand Q&A content on your pages, making you eligible for the FAQ rich result in Google Search. When implemented correctly, your FAQs can appear directly in search results with expandable answers.

## What is FAQ Schema?

FAQ schema (`@type: FAQPage`) structures question-and-answer content in a way that search engines can understand and display as rich results.

**Benefits:**
- Eligibility for FAQ rich results in Google Search
- Questions appear directly in search results
- Expandable answers without clicking through
- Increased visibility and click-through rates
- Takes up more space in search results
- Helps answer user questions immediately

**Best For:**
- FAQ pages
- Support and help documentation
- Product Q&A sections
- Service information pages
- Troubleshooting guides
- "Common Questions" sections

## When to Use FAQ Schema

### ✓ Use FAQ Schema When:

**Content Format:**
- You have question-and-answer format content
- Each question has one definitive answer
- Questions are written by your organization (not users)
- Content is published by you, not user-generated

**Page Types:**
- Dedicated FAQ pages
- Support/help pages with Q&A
- Product pages with FAQ sections
- Service pages with common questions

**Content Quality:**
- Answers are comprehensive
- Questions are actually asked by users
- Content adds value
- Not just for SEO manipulation

### ✗ Don't Use FAQ Schema When:

- User-generated questions (use QAPage instead)
- Forum posts or discussions
- Questions with multiple answers or opinions
- Advertising or promotional purposes only
- Every question has "Contact us" as the answer
- Questions no one actually asks

**Note:** Violating Google's guidelines can result in manual actions against your site.

## Required Fields

FAQ schema has a simple structure:

**FAQ Items** (Repeater) *
- Each item contains:
  - **Question** * (Required)
  - **Answer** * (Required)

That's it! FAQ schema is one of the simplest schema types.

## Step-by-Step Setup

### Step 1: Create FAQ Template

1. Navigate to **Swift Rank → Add New**
2. Title: "FAQ Schema - Support Pages"
3. Don't publish yet

### Step 2: Select Schema Type

In the **Swift Rank** panel:

1. **Schema Type** dropdown → Select **FAQ Page**
2. Panel refreshes with FAQ fields

### Step 3: Add FAQ Items

You'll see an **FAQ Items** section with a repeater field.

**To add a question:**

1. Click **Add Item**
2. **Question** field appears
3. **Answer** field appears
4. Fill both fields

**Example:**

**Question:**
```
How do I install the plugin?
```

**Answer:**
```
To install the plugin: 1) Navigate to Plugins → Add New in your WordPress dashboard. 2) Search for the plugin name. 3) Click Install Now, then Activate. The plugin is now active and ready to use.
```

**To add more questions:**

5. Click **Add Item** again
6. Fill in the next question and answer
7. Repeat for all FAQ items
8. Use drag handles to reorder items
9. Click × (remove) to delete items

### Step 4: Configure Multiple FAQs

**For FAQ Page Template:**

Add all your common questions and answers directly in the template:

```
Item 1:
Q: How do I install the plugin?
A: [Detailed installation instructions]

Item 2:
Q: Is there a Pro version?
A: [Pro version details]

Item 3:
Q: How do I get support?
A: [Support information]

... (add 5-15 items)
```

**For Per-Page FAQ:**

If each page has different FAQs, leave items empty and use post metabox to add page-specific FAQs (Pro feature).

### Step 5: Set Display Conditions

Scroll to **Display Conditions**:

**For Specific FAQ Pages:**
```
Condition Group 1:
└── Singular = FAQ Page ID(s)
```

**For Support Pages Category:**
```
Condition Group 1:
└── Post Type = Pages
    (Then manually select support pages)
```

**For Pages with "FAQ" in Title:**
```
Condition Group 1:
└── Post Type = Pages
    (Create multiple templates or manually select pages)
```

### Step 6: Publish Template

1. Review all question-answer pairs
2. Verify at least 2-3 FAQs present
3. Click **Publish**
4. Template is now active

### Step 7: Test Your Schema

1. Visit your FAQ page (frontend)
2. Admin bar → **Swift Rank** → **Google Rich Results Test**
3. Verify:
   - ✓ FAQ Page detected
   - ✓ All questions listed
   - ✓ No errors
   - ✓ Answers complete

## Field Configuration Reference

### Question Field

**Purpose:** The question being asked

**Format:** Plain text, clear question format

**Best Practices:**
- Write as actual questions
- Use natural language
- Start with Who, What, When, Where, Why, How
- Keep concise (under 100 characters ideal)
- Match how users actually search

**Good Examples:**
```
✓ How do I reset my password?
✓ What payment methods do you accept?
✓ Is shipping free?
✓ Where can I find the documentation?
✓ Why isn't my plugin working?
```

**Poor Examples:**
```
✗ Password Reset (not a question)
✗ For payment info, see here (not a question)
✗ Click here for shipping details (not a question)
```

### Answer Field

**Purpose:** The definitive answer to the question

**Format:** Plain text or HTML (HTML stripped in schema)

**Best Practices:**
- Provide complete, accurate answers
- Be concise but thorough
- Use clear, simple language
- Include steps if applicable
- Don't just link elsewhere
- Can be 1 sentence or multiple paragraphs

**Good Examples:**
```
✓ "To reset your password: 1) Click 'Forgot Password' on the login page. 2) Enter your email address. 3) Check your email for a reset link. 4) Follow the link and create a new password. Your password must be at least 8 characters long and include a number."

✓ "We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, Apple Pay, and Google Pay. All payments are processed securely through Stripe."

✓ "Yes, we offer free shipping on all orders over $50 within the United States. Orders under $50 have a flat shipping rate of $5.99. International shipping rates vary by location and are calculated at checkout."
```

**Poor Examples:**
```
✗ "See our password reset page." (not self-contained)
✗ "Yes." (too brief, not helpful)
✗ "Contact support for help." (not answering the question)
```

## Complete Example

**Template Name:** "FAQ Schema - Support Center"

**Schema Type:** FAQ Page

**FAQ Items:**

```
Item 1:
Q: How do I install Swift Rank?
A: To install Swift Rank: 1) Log in to your WordPress admin dashboard. 2) Navigate to Plugins → Add New. 3) Search for "Swift Rank". 4) Click Install Now on the Swift Rank plugin. 5) Click Activate. The plugin is now installed and you can access it from the Swift Rank menu in your dashboard.

Item 2:
Q: Do I need Swift Rank Pro?
A: Swift Rank Pro is optional. The free version includes essential schema types (Article, Organization, Person, LocalBusiness, FAQ, and more) suitable for most websites. Swift Rank Pro adds advanced types (Recipe, Event, How-To), additional features (custom schema builder, advanced conditions), and priority support. If you need advanced schema types or features, Pro is recommended.

Item 3:
Q: How do I know if my schema is working?
A: To verify your schema is working: 1) Visit a page with schema on the frontend while logged in. 2) Look at the admin bar and hover over "Swift Rank". 3) Click "Google Rich Results Test". 4) Google will analyze your page and show if schema is detected. You can also view your page source (right-click → View Page Source) and search for "application/ld+json" to see the schema markup directly.

Item 4:
Q: Can I use Swift Rank with other SEO plugins?
A: Yes, Swift Rank works alongside other SEO plugins like Yoast SEO, Rank Math, and All in One SEO. However, you may want to disable schema features in other plugins to avoid duplicate schema markup. Swift Rank is designed to be the comprehensive solution for structured data on your site.

Item 5:
Q: Where can I get support?
A: Free plugin support is available through the WordPress.org support forums. Swift Rank Pro users receive priority email support through our helpdesk at support.toolpress.net. Documentation and guides are available in the plugin's .docs folder and on our website at toolpress.net/schema-engine.
```

**Display Conditions:**
```
Condition Group 1:
└── Singular = FAQ Page (ID: 42)
```

**Expected Output:**
```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://example.com/faq#faqpage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How do I install Swift Rank?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "To install Swift Rank: 1) Log in to your WordPress admin dashboard. 2) Navigate to Plugins → Add New..."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need Swift Rank Pro?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Swift Rank Pro is optional. The free version includes essential schema types..."
      }
    },
    {
      "@type": "Question",
      "name": "How do I know if my schema is working?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "To verify your schema is working: 1) Visit a page with schema on the frontend..."
      }
    },
    {
      "@type": "Question",
      "name": "Can I use Swift Rank with other SEO plugins?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, Swift Rank works alongside other SEO plugins like Yoast SEO..."
      }
    },
    {
      "@type": "Question",
      "name": "Where can I get support?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Free plugin support is available through the WordPress.org support forums..."
      }
    }
  ]
}
```

## Google Guidelines for FAQ Schema

Google has strict policies for FAQ rich results. Violating them can result in manual actions.

### ✓ Do:

- Use for genuine FAQs that users ask
- Provide complete answers
- Write original content
- Keep content accurate and up-to-date
- Use on pages with FAQ content visible to users
- Ensure questions and answers match page content

### ✗ Don't:

- Use for advertising or promotional purposes
- Use for user-generated Q&A (use QAPage instead)
- Include obscene, profane, or offensive content
- Use for every question on your site
- Manipulate with fake questions
- Hide FAQ content from users
- Use for question-answer pairs you don't own

**Source:** [Google FAQ Rich Results Guidelines](https://developers.google.com/search/docs/appearance/structured-data/faqpage)

## FAQ vs QAPage Schema

**FAQPage:**
- Content written by website owner
- Official answers from organization
- Not user-generated
- Single definitive answer per question

**QAPage:**
- User-generated questions
- Community answers
- Forum or discussion format
- Multiple potential answers
- Voting or accepted answers

**Examples:**

| Use FAQPage | Use QAPage |
|-------------|------------|
| Company FAQ page | Stack Overflow questions |
| Product support Q&A | Reddit discussions |
| Service information | Quora questions |
| Help documentation | Community forums |

**Swift Rank Free:** FAQPage only
**Swift Rank Pro:** FAQPage and QAPage

## Advanced Configurations

### Dynamic FAQs per Page

**Scenario:** Each page has different FAQs

**Solution:** Use post metabox (Pro feature)

**Steps:**
1. Create FAQ template with empty items
2. Set display conditions (e.g., Post Type = Pages)
3. Edit each page
4. Use Swift Rank metabox to add page-specific FAQs

### FAQs on Multiple Page Types

**Scenario:** FAQs on products, services, and support pages

**Solution:** Create multiple templates or broad conditions

**Template 1: Product FAQs**
```
Title: FAQ - Products
Conditions: Post Type = Products
Items: Product-specific questions
```

**Template 2: Service FAQs**
```
Title: FAQ - Services
Conditions: Post Type = Services
Items: Service-specific questions
```

**Template 3: General FAQs**
```
Title: FAQ - Support
Conditions: Singular = FAQ Page
Items: General support questions
```

### Combining with Other Schema

FAQ schema can appear alongside other schema types:

**Product + FAQ:**
```
Product schema for product details
+ FAQ schema for product questions
= Both appear on product page
```

**Article + FAQ:**
```
Article schema for blog post
+ FAQ schema for FAQ section in article
= Both schemas output
```

Swift Rank automatically combines them in a schema graph.

## Common Issues

### Issue: "FAQ not detected" in Google

**Causes:**
1. Template not published
2. Display conditions don't match page
3. No FAQ items added
4. Questions or answers empty

**Solutions:**
1. Verify template is Published
2. Check display conditions match page
3. Add at least 2-3 FAQ items
4. Fill both question and answer for each item

### Issue: FAQ appears on wrong pages

**Cause:** Display conditions too broad

**Solutions:**
1. Review display conditions
2. Use Singular condition for specific pages
3. Test on various pages
4. Make conditions more specific

### Issue: Answers cut off in rich results

**Cause:** Google truncates long answers in preview

**Solution:**
- This is normal behavior
- Users click to see full answer
- Keep important info at the start
- Google shows what it deems relevant

### Issue: FAQ rich results not showing in search

**Causes:**
1. Schema has errors (check validator)
2. Page not indexed yet
3. Content violates guidelines
4. Google chooses not to show (their decision)
5. Not enough authority/trust

**Solutions:**
1. Validate schema (no errors)
2. Request indexing in Search Console
3. Review Google's FAQ guidelines
4. Be patient (can take weeks)
5. Build site authority

**Note:** FAQ rich results are not guaranteed. Google decides when to show them.

### Issue: Duplicate FAQ schema

**Causes:**
1. Multiple templates matching same page
2. Theme or other plugin adding FAQ schema
3. Manually added and template added

**Solutions:**
1. Review all templates' conditions
2. Disable FAQ schema in other plugins
3. Check theme settings
4. Keep only one FAQ schema per page

## Best Practices

### Content Quality

1. **Real Questions:** Use questions users actually ask
2. **Complete Answers:** Don't just say "Contact us"
3. **Natural Language:** Write like humans speak
4. **Accurate Info:** Keep answers up-to-date
5. **Helpful Content:** Add value, don't manipulate for SEO

### Schema Implementation

1. **Minimum 2-3 FAQs:** Google recommends at least 2
2. **Maximum 10-15 FAQs:** Keep focused and relevant
3. **One FAQ Schema Per Page:** Don't duplicate
4. **Match Visible Content:** Schema should match page
5. **Keep Updated:** Remove outdated Q&As

### Question Writing

1. **Start with Question Words:** How, What, Why, When, Where
2. **Be Specific:** Clear, focused questions
3. **User Intent:** Match search queries
4. **Natural Phrasing:** How users actually ask
5. **Concise:** Under 100 characters ideal

### Answer Writing

1. **Self-Contained:** Complete answer, not just a link
2. **Structured:** Use steps for processes
3. **Clear Language:** Avoid jargon
4. **Appropriate Length:** Long enough to be useful, short enough to be readable
5. **Actionable:** Tell users what to do

## Testing Checklist

Before considering FAQ schema complete:

- [ ] Template published
- [ ] Schema type set to FAQ Page
- [ ] At least 2-3 FAQ items added
- [ ] All questions filled
- [ ] All answers filled (complete answers)
- [ ] Questions written as questions (not statements)
- [ ] Answers are self-contained (not just links)
- [ ] Display conditions configured
- [ ] Tested on target page
- [ ] Google Rich Results Test shows FAQ detected
- [ ] No errors in validation
- [ ] FAQ content matches visible page content
- [ ] Content follows Google guidelines

## Rich Results Timeline

After implementing FAQ schema:

**Immediate:**
- Schema detected by validators
- Appears in page source

**1-2 Weeks:**
- Google crawls and indexes schema
- Search Console shows FAQ pages
- May start appearing in search

**1-3 Months:**
- Rich results may appear in search
- Depends on site authority and content quality
- Not guaranteed

**Check Status:**
- Google Search Console → Enhancements → FAQ
- URL Inspection tool for specific pages
- Search for your content and check results

## Monitoring and Maintenance

### Google Search Console

**1. Navigate to Enhancements → FAQ**
```
View:
- Valid FAQ pages
- Warnings
- Errors
```

**2. Monitor Performance**
```
Check:
- Number of FAQs indexed
- Impressions from FAQ rich results
- Click-through rates
```

### Regular Maintenance

**Monthly:**
- Review FAQ content for accuracy
- Update answers as information changes
- Add new common questions
- Remove outdated questions

**After Changes:**
- Validate schema after updates
- Request re-indexing in Search Console
- Monitor for errors

## Next Steps

- **Add More Schema Types:** [Article Schema](article.md)
- **Optimize Organization:** [Organization Schema](organization.md)
- **Test Thoroughly:** [Testing Schema](../testing-schema.md)
- **Master Variables:** [Dynamic Variables](../dynamic-variables.md)

FAQ schema is one of the easiest and most impactful schema types to implement. Add it to your FAQ pages today and start appearing in rich results!

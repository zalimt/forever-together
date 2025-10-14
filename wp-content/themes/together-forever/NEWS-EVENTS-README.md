# News and Events Templates

This document explains how to use the new News and Events page templates.

## Files Created

1. **news.php** - Template for News page
2. **events.php** - Template for Events page  
3. **scss/blog.scss** - Shared styles for blog/news/events pages
4. **css/main.css** - Updated compiled CSS (includes blog styles)

## How to Use

### Step 1: Create Pages in WordPress

1. Log into WordPress admin
2. Go to **Pages → Add New**
3. Create a page titled "News"
4. In the **Page Attributes** section (right sidebar), select **Template: News**
5. Publish the page

Repeat for "Events" page:
1. Create a page titled "Events"
2. Select **Template: Events**
3. Publish the page

### Step 2: Create Categories

1. Go to **Posts → Categories**
2. Create a category called "news" (slug: news)
3. Create a category called "events" (slug: events)

### Step 3: Assign Posts to Categories

Your existing test posts should already be assigned to these categories, but to create new posts:

1. Go to **Posts → Add New**
2. Create your post content
3. In the **Categories** section (right sidebar), check "News" or "Events"
4. Publish the post

## Optional: ACF Fields

You can create custom ACF fields for the page headers:

### For News Page:
- Field Name: `news_heading`
- Field Type: Text
- Field Name: `news_subheading`
- Field Type: Textarea

### For Events Page:
- Field Name: `events_heading`
- Field Type: Text
- Field Name: `events_subheading`
- Field Type: Textarea

If these fields are not set, the templates will use default text.

## Design Features

- **Purple/Pink color scheme** matching your Together Forever branding
- **Responsive grid layout** (3 columns on desktop, 2 on tablet, 1 on mobile)
- **Search functionality** to search within news or events
- **Pagination** for posts (9 posts per page)
- **Category filtering** - News page shows only "news" category, Events page shows only "events" category
- **Hover effects** on cards and buttons
- **Featured image support** for each post

## Customization

To rebuild CSS after making changes to SCSS:

```bash
cd /Users/zalimtsorionov/Local\ Sites/forever-together/app/public/wp-content/themes/together-forever
npm run build
```

Or to watch for changes:

```bash
npm run watch
```

## Colors Used

- Primary Purple: `#5C2483`
- Secondary Pink: `#951B81`
- Gray: `#333`
- Background: `#f8f6ff`
- Font: Avenir Next Cyr


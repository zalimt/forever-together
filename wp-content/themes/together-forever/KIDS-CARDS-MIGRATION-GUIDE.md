# Kids Cards Migration Guide

## Overview
The kids cards functionality has been migrated from ACF repeater fields to the **Kids** custom post type. This provides better content management and scalability.

## What Changed

### Before:
- Kids cards were managed through ACF repeater field on the Home page
- Status was a select field in the repeater

### Now:
- Kids cards are managed as individual **Kids** posts
- Status is determined by the **Kid Category** taxonomy
- Kid's name (on card) = ACF field "Kids Card Name"
- Post title = Used for the post page itself (can be same as card name or different)
- Kid's bio = Post content/editor
- Other fields (age, diagnosis, amounts, links) = ACF fields on each Kids post

## Setup Instructions

### 1. Create Kid Categories
First, you need to create three categories for the Kids post type:

1. Go to **WordPress Admin → Kids → Categories**
2. Create the following three categories (exact names are important):
   - **In Need of Help**
   - **Awaiting Treatment**
   - **We Helped**

### 2. Create Kids Posts
For each child:

1. Go to **WordPress Admin → Kids → Add New**
2. Fill in the following:
   - **Title**: Enter the child's name (used for the post page, can be same as card name)
   - **Content Editor**: Enter the child's biography/story (used for the post content)
   - **Featured Image** (optional): Set the main image for the child
   - **Category**: Select the appropriate status category

3. Scroll down to the **Kids Cards Post** ACF fields section and fill in:
   - **Kids Card Name**: Enter the child's name as it should appear on the card
   - **Kid Card Image**: Upload the child's photo (or use Featured Image)
   - **Collected Amount**: Enter the amount collected so far
   - **Required Amount**: Enter the total amount needed
   - **Kid Age**: Enter the child's age
   - **Kid Diagnosis**: Enter the diagnosis
   - **Donate BTN Link**: Enter the donation link URL

4. Click **Publish**

### 3. Moving Existing Kids Data
If you have existing kids in the old ACF repeater field:

1. Go to **Pages → Home**
2. Open the Kids Cards Section
3. For each kid in the repeater:
   - Create a new Kids post with the same information
   - Copy the data to the appropriate fields
   - Assign the correct category based on the old Status field
4. Once all kids are migrated, you can keep or remove the old ACF repeater field

## How It Works

### Front Page (front-page.php)
- Only shows kids with the **"In Need of Help"** category
- Displays them in descending order by publish date (newest first)

### Our Beneficiaries Page (our-beneficiaries.php)
Has three tabs, each showing kids from a different category:
- **Tab 1 (In Need of Help)**: Shows kids with "In Need of Help" category
- **Tab 2 (Awaiting Treatment)**: Shows kids with "Awaiting Treatment" category
- **Tab 3 (We Helped)**: Shows kids with "We Helped" category

## Managing Kids

### To Add a New Child:
1. Create a new Kids post
2. Fill in all required information
3. Assign to "In Need of Help" category
4. Publish

### To Update a Child's Status:
1. Edit the Kids post
2. Change the Kid Category to the new status
3. Update the post

### To Remove a Child from Display:
- Option 1: Move to "We Helped" category
- Option 2: Change post status to Draft or move to Trash

## Benefits of the New System

1. **Better Organization**: Each child has their own dedicated post
2. **Individual URLs**: Each child can have their own page with full biography
3. **Better SEO**: Individual posts are better for search engines
4. **Easier Management**: No need to scroll through a long repeater field
5. **More Flexible**: Can easily add more fields or functionality in the future
6. **Better Performance**: Posts are cached better than ACF repeater fields

## Field Mapping Reference

| Old ACF Field | New Location |
|--------------|--------------|
| Kid Name | ACF Field: "Kids Card Name" |
| Kids Bio | Post Content (Editor) |
| Status | Kid Category Taxonomy |
| Kid Card Image | ACF Field (or Featured Image) |
| Collected Amount | ACF Field |
| Required Amount | ACF Field |
| Kid Age | ACF Field |
| Kid Diagnosis | ACF Field |
| Donate BTN Link | ACF Field |
| More About a Child Link | Auto-generated Post Permalink |

**Note:** The Post Title is separate from the Kids Card Name. You can use the same name for both, or have different names for organization purposes.

## Troubleshooting

### No kids showing up?
1. Make sure the Kids posts are Published (not Draft)
2. Verify the categories are named exactly: "In Need of Help", "Awaiting Treatment", "We Helped"
3. Make sure the kids are assigned to the correct category

### Images not showing?
1. Make sure either the ACF "Kid Card Image" is set OR the Featured Image is set
2. Check that the images are properly uploaded to the Media Library

### Links not working?
1. The "More About a Child" link automatically uses the post's permalink
2. Make sure permalinks are enabled (Settings → Permalinks)
3. You may need to flush permalinks: Settings → Permalinks → Save Changes

## Questions or Issues?
If you encounter any problems, check that:
1. The Kids custom post type is active
2. The kid_category taxonomy exists
3. The three categories are created with exact names
4. ACF fields are properly set up for the Kids post type


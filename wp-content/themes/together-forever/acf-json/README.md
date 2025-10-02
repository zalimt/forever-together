# ACF Pro JSON Configuration

This directory contains JSON files for Advanced Custom Fields (ACF) Pro field group configurations.

## How ACF JSON Works

ACF Pro can automatically save and load field group configurations from JSON files. This allows you to:

- **Version Control**: Track field changes in Git
- **Deploy Fields**: Automatically sync fields across environments
- **Backup Fields**: Keep field configurations safe
- **Team Collaboration**: Share field structures with team members

## Setup Instructions

### 1. Enable JSON Save/Load

Add this to your theme's `functions.php`:

```php
/**
 * ACF JSON Configuration
 */
function together_forever_acf_json_save_point($path) {
    // Update path to point to your theme's acf-json folder
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'together_forever_acf_json_save_point');

function together_forever_acf_json_load_point($paths) {
    // Remove the original path
    unset($paths[0]);
    // Add the new path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'together_forever_acf_json_load_point');
```

### 2. Create Field Groups

1. Go to **Custom Fields > Field Groups** in WordPress admin
2. Create your field groups as usual
3. ACF will automatically save JSON files to this directory
4. Commit the JSON files to Git

### 3. Deploy to Other Environments

1. Copy the JSON files to the same directory on other sites
2. Go to **Custom Fields > Field Groups** in WordPress admin
3. ACF will automatically detect and load the field groups

## File Structure

```
acf-json/
├── group_xxxxxxxxx.json    # Field group JSON files
├── group_yyyyyyyyy.json    # (ACF generates these automatically)
└── README.md              # This file
```

## Field Group Naming Convention

ACF generates JSON files with this naming pattern:
- `group_[field_group_key].json`

Example: `group_507f1f77bcf86cd799439011.json`

## Best Practices

### 1. Always Use JSON
- Never edit field groups directly in the database
- Always use the ACF admin interface
- Let ACF handle the JSON file generation

### 2. Version Control
- Commit all JSON files to Git
- Include meaningful commit messages when adding/updating fields
- Review field changes in pull requests

### 3. Field Group Organization
- Use descriptive field group titles
- Group related fields together
- Use field group rules to show/hide fields appropriately

### 4. Field Naming
- Use consistent naming conventions
- Use descriptive field names
- Use field labels for user-friendly display

## Example Field Group Structure

```json
{
  "key": "group_507f1f77bcf86cd799439011",
  "title": "Page Settings",
  "fields": [
    {
      "key": "field_507f1f77bcf86cd799439012",
      "label": "Page Subtitle",
      "name": "page_subtitle",
      "type": "text",
      "instructions": "Enter a subtitle for this page",
      "required": 0,
      "conditional_logic": 0,
      "wrapper": {
        "width": "",
        "class": "",
        "id": ""
      },
      "default_value": "",
      "placeholder": "",
      "prepend": "",
      "append": "",
      "maxlength": ""
    }
  ],
  "location": [
    [
      {
        "param": "post_type",
        "operator": "==",
        "value": "page"
      }
    ]
  ],
  "menu_order": 0,
  "position": "normal",
  "style": "default",
  "label_placement": "top",
  "instruction_placement": "label",
  "hide_on_screen": "",
  "active": true,
  "description": "Custom fields for page settings"
}
```

## Troubleshooting

### Fields Not Loading
1. Check that the JSON files are in the correct directory
2. Verify the `acf/settings/load_json` filter is working
3. Check file permissions on the JSON files

### Fields Not Saving
1. Check that the JSON files are writable
2. Verify the `acf/settings/save_json` filter is working
3. Check WordPress file permissions

### Field Groups Not Showing
1. Check the field group location rules
2. Verify the field group is active
3. Check if there are any PHP errors

## Integration with Theme

You can use ACF fields in your theme templates:

```php
// Get field value
$subtitle = get_field('page_subtitle');

// Check if field exists
if (get_field('page_subtitle')) {
    echo '<h2>' . get_field('page_subtitle') . '</h2>';
}

// Get field with default value
$subtitle = get_field('page_subtitle') ?: 'Default Subtitle';
```

## Resources

- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [ACF JSON Documentation](https://www.advancedcustomfields.com/resources/local-json/)
- [ACF Field Types](https://www.advancedcustomfields.com/resources/#field-types)

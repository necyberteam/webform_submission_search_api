# SUMMARY

The Webform Submission Search API module provides search_api processors to index fields on Webform Submissions and Event Instances.

## PROCESSORS

### Webform Submission Processors
- **WebformSubmissionDomain**: Indexes domain access fields from webform submissions
- Additional processors for Title, Description, text, Flagging, and Taxonomy reference fields

### Event Instance Processors  
- **EventInstanceDomain**: Indexes domain access fields from event instances to enable domain-specific filtering in search results and facets

Once indexed, the fields can be used elsewhere, e.g. in Views and Search API facets.

## REQUIREMENTS

- Search API
- Webform
- Recurring Events (for Event Instance processors)
- Domain Access (for domain filtering)

## USAGE

Add the fields you want to index to the Search API index.

After indexing is performed you can add this field into a view or configure facets that respect domain access filtering.

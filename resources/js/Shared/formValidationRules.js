export const validationRules = {
	required: value => !!value || 'This field is required.',
	isbn: value => value.length >= 10 || 'ISBN must be at least 10 digits long',
	year: value => value > 0 || 'Must be a valid year'
  };
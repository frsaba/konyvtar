import axios from "axios";

export async function getBookWithISBN(isbn){
	let response = (await axios.get(`/books/isbn?isbn=${isbn}`)).data
	return response
}

export const validationRules = {
	required: value => !!value || 'This field is required.',
	isbn: value => value?.length >= 10 || 'ISBN must be at least 10 digits long',
	isbn_not_exists: async value => (await getBookWithISBN(value)) == -1 || 'A book with this ISBN already exists',
	year: value => value > 0 || 'Must be a valid year'
  };
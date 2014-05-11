/**
 * Clears a text field if the value equals the given text-variable.
 * @author Jonas Dahl
 * @param object the text field to empty
 * @param text if the text field contains this text - it will be emptied
 * @return true if emptied, false else
 */
function clearField(object, text) {
	if (object.value == text) {
		object.value = "";
		return true;
	}
	return false;
}

/**
 * Updates a text field to show the desired text if it is empty.
 * @author Jonas Dahl
 * @param object the text field to empty
 * @param text to update text field with
 * @return true if changes, false else
 */
function resetField(object, text) {
	if (object.value == "") {
		object.value = text;
		return true;
	}
	return false;
}
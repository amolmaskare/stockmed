export const logout = () => (dispatch) => {
    localStorage.removeItem("userToken"); // Remove token from storage
    dispatch({ type: "LOGOUT" }); // Dispatch logout action
};
// reducers/authReducer.js
const authReducer = (state = { user: null }, action) => {
    switch (action.type) {
        case "LOGOUT":
            return { ...state, user: null };
        default:
            return state;
    }
};
export default authReducer;

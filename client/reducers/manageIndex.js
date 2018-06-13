import {combineReducers} from 'redux'
import mainReducer from './managePageReducer'

import undoable from 'redux-undo'
import actionTypes from 'ACTION_TYPES'

let includeActions = [
  actionTypes.UPDATE_STATE_DATA,
];
let customFilter = (action, currentState, previousHistory) => {
  if(includeActions.indexOf(action.type) !== -1) {
    return true;
  }
  return false;
};
const allReducers = combineReducers({
  main: undoable(mainReducer, {
    filter: customFilter,
    limit: 100
  }),
})

export default allReducers;

import React from 'react'
import ReactDOM from 'react-dom'
import ManageDataPage from './components/ManageDataPage'
import {Provider} from 'react-redux'
import store from './store/managePageConfigureStore'
import {bootstrapApp} from 'ISD_API'
//import rootSaga from './sagas';
const rootEl = document.getElementById('isd_app')
ReactDOM.render(
  <Provider store={store}>
    <ManageDataPage/>
  </Provider>
  ,
  rootEl
);


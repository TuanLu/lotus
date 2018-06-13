import React from 'react'
import ReactDOM from 'react-dom'
import ReportPage from './components/ReportPage'
import {Provider} from 'react-redux'
import store from './store/configureStoreProduction'
import {bootstrapApp} from 'ISD_API'

const rootEl = document.getElementById('isd_app')
ReactDOM.render(
  <Provider store={store}>
    <ReportPage/>
  </Provider>
  ,
  rootEl
);


import React, {Component} from 'react'
import {connect} from 'react-redux'
import Bar from './Chart/Bar'
import {updateStateData} from 'actions'
import FilterByYear from './Filter/FilterByYear'
import FilterByProduct from './Filter/FilterByProduct'
import FilterByArea from './Filter/FilterByArea'
import FilterLabel from './Filter/FilterLabel'

class ReportByArea extends Component{
  render() {
    let {year, product, area} = this.props.mainState.filter;
    return (
      <React.Fragment>
        <div className="ui segment">
          <FilterLabel/>
          <FilterByYear/>
          {" | "}
          <FilterByArea/>
          {" | "}
          <FilterByProduct/>
        </div>
        <div className="grid ui equal width">
          <div className="column">
            <Bar 
              url={`${ISD_BASE_URL}report?year=${year}&type=areas&product-id=${product}`}/>
          </div>
        </div>
        <div className="grid ui equal width">
          <div className="column">
            <Bar
              url={`${ISD_BASE_URL}report?year=${year}&type=area_quarter&product-id=${product}&area=${area}`}/>
          </div>
        </div>
        <div className="ui grid equal width">
          <div className="column">
            <Bar
               url={`${ISD_BASE_URL}report?year=${year}&type=area_month&product-id=${product}&area=${area}`}
            />
          </div>
        </div>
        <div className="ui grid equal width">
          <div className="column">
            <Bar
              url={`${ISD_BASE_URL}report?year=${year}&type=area_week&product-id=${product}&area=${area}`}
            />
          </div>
        </div>      
      </React.Fragment>
    );
  }
};
export default connect((state) => {
  return {
    mainState: state.main.present,
    uiState: state.ui,
  }
})(ReportByArea);

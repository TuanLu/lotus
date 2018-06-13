import React, { Component } from 'react'
import { connect } from 'react-redux'
import Sidebar from './ReportPage/Sidebar'
import ReportComponent from './ReportPage/ReportComponent'
import ReportByArea from './ReportPage/ReportByArea'
import ReportByProvinces from './ReportPage/ReportByProvinces'
import ReportByStores from './ReportPage/ReportByStores'
import Loading from './Loading'
import LoginForm from './LoginForm'
import {getTokenHeader} from 'ISD_API'
import {updateStateData} from 'actions'
import UserInfo from './UserInfo';

//Global config chart
Chart.defaults.global.hover.mode = 'nearest';
Chart.defaults.global.defaultFontColor = '#000';
Chart.defaults.global.defaultFontFamily = "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
Chart.defaults.global.defaultFontSize = 16;
// Define a plugin to provide data labels
Chart.plugins.register({
  afterDatasetsDraw: function(chart) {
    var ctx = chart.ctx;

    chart.data.datasets.forEach(function(dataset, i) {
      var meta = chart.getDatasetMeta(i);
      if(!chart.config.options.showTooltip) return;
      if (!meta.hidden) {
        meta.data.forEach(function(element, index) {
          // Draw the text in black, with the specified font
          ctx.fillStyle = 'rgb(0, 0, 0)';

          var fontSize = 12;
          var fontStyle = 'normal';
          var fontFamily = 'Helvetica Neue';
          ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

          // Just naively convert to string for now
          var dataString = dataset.data[index].toString();

          // Make sure alignment settings are correct
          ctx.textAlign = 'center';
          ctx.textBaseline = 'middle';

          var padding = 5;
          var position = element.tooltipPosition();
          ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
        });
      }
    });
  }
});

class ReportPage extends Component {
  constructor(props) {
    super(props);
    this.renderReport = this.renderReport.bind(this);
    this.state = {
      loading: true
    }
  }
  renderReport() {
    let {reportBy} = this.props.mainState;
    switch (reportBy) {
      case 'theo_mien':
        return <ReportByArea/>
        break;
      case 'theo_tinh':
        return <ReportByProvinces/>
        break;
      case 'theo_nha_thuoc':
        return <ReportByStores/>
        break;
      default:
        return <ReportComponent/>
        break;
    }
  }
  componentWillMount() {
    let {mainState} = this.props;
    if(!mainState.userInfo) {
      let token = sessionStorage.getItem('ISD_TOKEN');
      if(token != "" && token != null) {
        fetch(ISD_BASE_URL + 'fetchRoles', {
          headers: getTokenHeader()   
        })
        .then((response) => response.json())
        .then((json) => {
          if(json.userInfo) {
            this.props.dispatch(updateStateData({
              showLogin: false,
              userRoles: json.scopes,
              defaultRouter: json.scopes[0] && json.scopes[0]['path'] ? json.scopes[0]['path'] : '',
              userInfo: json.userInfo
            }));
          } else if(json.status == "error") {
            alert(json.message);
          }
          this.setState({loading: false});
        })
        .catch((error) => {
          console.warn(error);
          this.setState({loading: false});
        });
      } else {
        this.setState({loading: false});
      }
    } else {
      this.props.dispatch(updateStateData({
        defaultRouter: mainState.defaultRouter
      }));
    }
  }
  render() {
    if(this.state.loading) {
      return <Loading/>
    }
    let { year, product } = this.props.mainState.filter;
    let {showLogin } = this.props.mainState;
    if(showLogin) {
      return (
        <LoginForm dispatch={this.props.dispatch}/>
      );
    }
    return (
      <React.Fragment>
        <div className="grid-container">
          <div className="header">
            <div className="header_left">
              
            </div>
            <div className="header_center">
              <h2>QUẢN TRỊ KHÁCH HÀNG</h2>
            </div>
            <div className="header_right">
              <UserInfo dispatch={this.props.dispatch} mainState={this.props.mainState}/>
            </div>
          </div>
          <div className="menu menu_active">
            <a href="#"><img width="200" src="images/logo-pharma.png" /></a>
            <Sidebar/>
          </div>
          <div className="isd_main">
            <div className="main_content">
              <div className="main_content_left active" page="main">
                <div className="list_infos">
                  {this.renderReport()}
                </div>
              </div>
            </div>
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
})(ReportPage);

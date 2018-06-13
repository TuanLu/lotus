import React, { Component } from 'react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'
class SidebarComponent extends Component {
  constructor(props) {
    super(props);
    this.showReportBy = this.showReportBy.bind(this);
    this.showMenu = this.showMenu.bind(this);
  }
  showReportBy(type) {
    this.props.dispatch(updateStateData({
      pageId: type
    }));
  }
  showChartMenu() {
    let {reportBy} = this.props.mainState;
    let menus = [
      {
        reportBy: 'doanh_thu_tong',
        title: 'Doanh thu theo năm',
      },
      {
        reportBy: 'theo_mien',
        title: 'Doanh thu theo miền'
      },
      {
        reportBy: 'theo_tinh',
        title: 'Doanh thu theo tỉnh',
        filter: {
          area: 'b',
          quarter: 1
        }
      },
      // {
      //   reportBy: 'theo_nha_thuoc',
      //   title: 'Doanh thu theo nhà thuốc',
      // },
    ];
    return menus.map((menu, index) => {
      return (
        <div 
          onClick={() => {
            window.location.href = ISD_BASE_URL + '?page=' + menu.reportBy;
          }}
          key={index} 
          className={`menu_item ${reportBy == menu.reportBy ? 'active': ''}`}>
          <div className="info">{menu.title}</div>
        </div>
      );
    });
  }
  showMenu() {
    let {pageId} = this.props.mainState;
    let menus = [
      {
        pageId: 'agency',
        title: 'Quản lý nhà phân phối',
      },
      {
        pageId: 'orders',
        title: 'Quản lý đơn hàng'
      },
      {
        pageId: 'stores',
        title: 'Quản lý nhà thuốc',
      },
      {
        pageId: 'products',
        title: 'Quản lý sản phẩm',
      },
      {
        pageId: 'tdv',
        title: 'Quản lý TDV',
      },
      {
        pageId: 'exchange',
        title: 'Quản lý Đơn vị tính',
      },
      {
        pageId: 'plan',
        title: 'Quản lý kế hoạch',
      },
    ];
    return menus.map((menu, index) => {
      return (
        <div 
          onClick={() => {
            let updateData = {
              pageId: menu.pageId
            };
            this.props.dispatch(updateStateData(updateData));
          }}
          key={index} 
          className={`menu_item ${pageId == menu.pageId ? 'active': ''}`}>
          <div className="info">{menu.title}</div>
        </div>
      );
    });
  }
  render() {
    return (
      <React.Fragment>
        <div className="main_menu" style={{marginTop: 33}}>
          {this.showChartMenu()}
          {this.showMenu()}
        </div>
      </React.Fragment>
    );
  }
};
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(SidebarComponent);
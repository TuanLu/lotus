import React from 'react';
import OrdersTableData from './ManageOrders/OrdersTableData'

export default class ManageOrders extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <div className="ui segment">
         <OrdersTableData url={this.props.url}/>
      </div>
    );
  }
}
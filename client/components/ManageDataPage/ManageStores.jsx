import React from 'react';
import StoresTableData from './ManageStores/StoresTableData'

export default class ManageStores extends React.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
      <div className="ui segment">
         <StoresTableData url={this.props.url}/>
      </div>
    );
  }
}
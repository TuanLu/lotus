import React from 'react';

export default class SmartControl extends React.Component {
  render() {
    return (
      <div className="table-controls ui segment">
        <div className="ui equal width grid">
          <div className="column">
            <h2>Quản lý đơn hàng</h2>
          </div>
          {/* <pre>{JSON.stringify(this.props, null, 2)}</pre> */}
          <div className="column right aligned">
            <div className="ui buttons">
              <button className="ui button primary">Phân tích dữ liệu</button>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
import React from 'react'

const wrapStyles = {
  position: 'absolute',
  zIndex: 10,
  marginTop: 8,
  marginRight: 14,
  top: 0,
  right: 0
};

export default class AbsFilterWrap extends React.Component {
  render() {
    return (
      <div className="wrap-filter" style={wrapStyles}>
        {this.props.filter}
      </div>
    );
  }
}
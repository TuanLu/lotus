import _ from 'lodash'
import React, { Component } from 'react'
import { Search } from 'semantic-ui-react'

export default class SearchDistrict extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false,
      results: [],
      value: ''
    };
  }
  // componentWillMount() {
  //   this.resetComponent()
  // }

  resetComponent = () => this.setState({ isLoading: false, results: [], value: '' })

  handleResultSelect = (e, { result }) => {
    if(this.props.onResultSelect) {
      this.props.onResultSelect(result);
    }
    this.setState({
      value: result.store_id
    })
  }
  
  static getDerivedStateFromProps(nextProps, prevState) {
    if(nextProps.districtId != prevState.value) {
      return {
        value: nextProps.districtId
      }
    }
    return true;
  }

  handleSearchChange = (e, { value }) => {
    this.setState({ isLoading: true, value })

    setTimeout(() => {
      if (this.state.value.length < 1) return this.resetComponent()

      const re = new RegExp(_.escapeRegExp(this.state.value), 'i')
      const isMatch = result => re.test(result.title)

      this.setState({
        isLoading: false,
        results: _.filter(this.props.source, isMatch),
      })
    }, 300)
  }

  render() {
    const { isLoading, value, results } = this.state
    return (
      <Search
        aligned="right"
        loading={isLoading}
        onResultSelect={this.handleResultSelect}
        onSearchChange={_.debounce(this.handleSearchChange, 500, { leading: true })}
        results={results}
        value={value}
        resultRenderer={({ title, description, district_id }) => (
          <div>{`${title}`}</div>
        )}
        fluid
        source={this.props.source}
      />
    )
  }
}

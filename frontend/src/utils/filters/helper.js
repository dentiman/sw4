const exchanges = {
  1: 'NYSE',
  2: 'NASDAQ',
  3: 'AMEX',
  4: 'NYSEARCA',
  6: 'BATS'
}
const exchangeColors = {
  1: 'green_c3',
  2: 'blue_c3',
  3: 'grey',
  4: 'red_c3',
  6: 'orange_c3'
}
const exchangeChoices =  () => {
  return Object.entries(exchanges).reduce((choices, pair) => {
    const [key, value] = pair

    choices.push({ value: key, text: value })

    return choices
  }, [])
}

export { exchanges,exchangeChoices, exchangeColors }

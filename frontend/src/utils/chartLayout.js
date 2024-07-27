const chartLayout = function(timeframe = 'd',width = 800,height =  500)  {
  const lightMode = !!localStorage.getItem('lightMode')

  return   {
    timeFrame: timeframe,
    'width': width,
    'height': height,
    'volumeAreaHeight': 40,
    'bgColor': lightMode ? '#FFFFFF' : '#2B2B2B' ,
    'gridColor': lightMode ? '#D6D6D6' : '#454545',
    'preMarket': false,
    'separateVolumeArea': true,
    'spyOn': false,
    'spyColor': '#ababab',
    'barType': 'candle',
    'barWidth': 6,
    'barThick': 1,
    'outline': 2,
    'volumeBarWidth': 4,
    'colorUp': '#249E92',
    'colorDown': '#E34F4C',
    'outlineColorUp': lightMode ?  '#000000' : '#249E92',
    'outlineColorDown': lightMode ?  '#000000' : '#E34F4C',
    'volumeColorUp': '#249E92',
    'volumeColorDown': '#E34F4C',
    'sma1': 0,
    'sma2': 0,
    'sma3': 0,
    'ema1': 0,
    'ema2': 0,
    'ema3': 0,
    'sma1Color': '#ababab',
    'sma2Color': '#ababab',
    'sma3Color': '#ababab',
    'ema1Color': '#ababab',
    'ema2Color': '#ababab',
    'ema3Color': '#ababab',
    'maLabel': false,
    'maLine': false,
    'linesOpen': false,
    'linesHigh': false,
    'linesLow': false,
    'linesClose': false,
    'lineLastPrice': false,
    'linesDays': 1
  }
}

const defaultPresetItem = (isMini = false, isPremium = true) => {
  if (isMini) {
    return {
      chartOne: chartLayout('d',500,300),
      chartTwo: null,
      chartThree: null,
      name:  null,
      displayFeedFields: [
        'vol','averageDailyVolume3Month','atr'
      ]
    }
  }

  return {
    chartOne: chartLayout(),
    chartTwo: isPremium ? chartLayout('5') : null,
    chartThree: null,
    name:  null,
    displayFeedFields: [
      'vol','averageDailyVolume3Month','atr'
    ]
  }
}

export { chartLayout, defaultPresetItem }

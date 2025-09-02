# Contributing

1. Fork the repository
2. Add your [trmnlp](https://github.com/usetrmnl/trmnlp) compatible recipe to the yaml, keys are sorted alphabetically.
Minimal structure should be

```yaml
{{author-username}}-{{recipe-name-slug}}:
  name: 'Plugin Name'
  trmnlp:
    repo: 'https://github.com/bnussbau/repository'                     # Code Repository
    zip_url: 'https://github.com/bnussbau/repository/archive/main.zip' # TRMNLP compatible ZIP file
  author:
    github: bnussbau
```

Full example:

```yaml
bnussbau-trmnl-austrian-train-departures:
  name: 'Austrian Train Departures (OEBB)'
  trmnlp:
    id: 28271
    repo: 'https://github.com/bnussbau/trmnl-austrian-train-departures/'
    zip_url: 'https://github.com/bnussbau/trmnl-austrian-train-departures/archive/d8f175e57d1378541c9b6eff545d4c96a426e29d.zip'
    version: 'd8f175e'
  logo_url: 'https://trmnl-public.s3.us-east-2.amazonaws.com/h9zmyi8yt117fehvb1cuw06v0wta'
  screenshot_url: 'https://github.com/user-attachments/assets/440f3c3e-ee12-4493-b94e-a2cd6e9e61f5'
  license: 'MIT'
  byos:
    byos_laravel:
      compatibility: true
      compatibility_note: null
      min_version: 0.13.0
  author:
    github: 'bnussbau'
    name: 'Ben Nussbaum'
  funding:
    custom: 'https://usetrmnl.com/?ref=laravel-trmnl'
    buy_me_a_coffee: bnussbau
    github: 'bnussbau'
  author_bio:
    description: 'Train departures for the Austrian OEBB. Data attribution dbf.finalrewind.org'
    github_url: 'https://github.com/bnussbau/trmnl-austrian-train-departures/'
    learn_more_url: 'https://github.com/bnussbau/trmnl-austrian-train-departures/blob/main/README.md'
```

3. Open a PR

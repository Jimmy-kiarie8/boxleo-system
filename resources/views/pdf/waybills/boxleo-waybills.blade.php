<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logixsaas</title>

    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> --}}
    {{-- <link href="{{ asset('css/pdf2.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/pdf3.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/http/bootstrap.css') }}" rel="stylesheet"> --}}

    <style>
        #packing td {
            padding: 0
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6
        }

        .table-sm td,
        .table-sm th {
            padding: .3rem
        }

        .table-bordered {
            border: 1px solid #dee2e6
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6
        }

        .table-bordered thead td,
        .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-borderless tbody+tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            border: 0
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, .075)
        }

        .table-primary,
        .table-primary>td,
        .table-primary>th {
            background-color: #b8daff
        }

        .table-primary tbody+tbody,
        .table-primary td,
        .table-primary th,
        .table-primary thead th {
            border-color: #7abaff
        }

        .table-hover .table-primary:hover {
            background-color: #9fcdff
        }

        .table-hover .table-primary:hover>td,
        .table-hover .table-primary:hover>th {
            background-color: #9fcdff
        }

        .table-secondary,
        .table-secondary>td,
        .table-secondary>th {
            background-color: #d6d8db
        }

        .table-secondary tbody+tbody,
        .table-secondary td,
        .table-secondary th,
        .table-secondary thead th {
            border-color: #b3b7bb
        }

        .table-hover .table-secondary:hover {
            background-color: #c8cbcf
        }

        .table-hover .table-secondary:hover>td,
        .table-hover .table-secondary:hover>th {
            background-color: #c8cbcf
        }

        .table-success,
        .table-success>td,
        .table-success>th {
            background-color: #c3e6cb
        }

        .table-success tbody+tbody,
        .table-success td,
        .table-success th,
        .table-success thead th {
            border-color: #8fd19e
        }

        .table-hover .table-success:hover {
            background-color: #b1dfbb
        }

        .table-hover .table-success:hover>td,
        .table-hover .table-success:hover>th {
            background-color: #b1dfbb
        }

        .table-info,
        .table-info>td,
        .table-info>th {
            background-color: #bee5eb
        }

        .table-info tbody+tbody,
        .table-info td,
        .table-info th,
        .table-info thead th {
            border-color: #86cfda
        }

        .table-hover .table-info:hover {
            background-color: #abdde5
        }

        .table-hover .table-info:hover>td,
        .table-hover .table-info:hover>th {
            background-color: #abdde5
        }

        .table-warning,
        .table-warning>td,
        .table-warning>th {
            background-color: #ffeeba
        }

        .table-warning tbody+tbody,
        .table-warning td,
        .table-warning th,
        .table-warning thead th {
            border-color: #ffdf7e
        }

        .table-hover .table-warning:hover {
            background-color: #ffe8a1
        }

        .table-hover .table-warning:hover>td,
        .table-hover .table-warning:hover>th {
            background-color: #ffe8a1
        }

        .table-danger,
        .table-danger>td,
        .table-danger>th {
            background-color: #f5c6cb
        }

        .table-danger tbody+tbody,
        .table-danger td,
        .table-danger th,
        .table-danger thead th {
            border-color: #ed969e
        }

        .table-hover .table-danger:hover {
            background-color: #f1b0b7
        }

        .table-hover .table-danger:hover>td,
        .table-hover .table-danger:hover>th {
            background-color: #f1b0b7
        }

        .table-light,
        .table-light>td,
        .table-light>th {
            background-color: #fdfdfe
        }

        .table-light tbody+tbody,
        .table-light td,
        .table-light th,
        .table-light thead th {
            border-color: #fbfcfc
        }

        .table-hover .table-light:hover {
            background-color: #ececf6
        }

        .table-hover .table-light:hover>td,
        .table-hover .table-light:hover>th {
            background-color: #ececf6
        }

        .table-dark,
        .table-dark>td,
        .table-dark>th {
            background-color: #c6c8ca
        }

        .table-dark tbody+tbody,
        .table-dark td,
        .table-dark th,
        .table-dark thead th {
            border-color: #95999c
        }

        .table-hover .table-dark:hover {
            background-color: #b9bbbe
        }

        .table-hover .table-dark:hover>td,
        .table-hover .table-dark:hover>th {
            background-color: #b9bbbe
        }

        .table-active,
        .table-active>td,
        .table-active>th {
            background-color: rgba(0, 0, 0, .075)
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, .075)
        }

        .table-hover .table-active:hover>td,
        .table-hover .table-active:hover>th {
            background-color: rgba(0, 0, 0, .075)
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #343a40;
            border-color: #454d55
        }

        .table .thead-light th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6
        }

        .table-dark {
            color: #fff;
            background-color: #343a40
        }

        .table-dark td,
        .table-dark th,
        .table-dark thead th {
            border-color: #454d55
        }

        .table-dark.table-bordered {
            border: 0
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, .05)
        }

        .table-dark.table-hover tbody tr:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, .075)
        }

        @media (max-width:575.98px) {
            .table-responsive-sm {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-sm>.table-bordered {
                border: 0
            }
        }

        @media (max-width:767.98px) {
            .table-responsive-md {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-md>.table-bordered {
                border: 0
            }
        }

        @media (max-width:991.98px) {
            .table-responsive-lg {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-lg>.table-bordered {
                border: 0
            }
        }

        @media (max-width:1199.98px) {
            .table-responsive-xl {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch
            }

            .table-responsive-xl>.table-bordered {
                border: 0
            }
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .table-responsive>.table-bordered {
            border: 0
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: .5rem
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        abbr[data-original-title],
        abbr[title] {
            text-decoration: underline;
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted;
            cursor: help;
            border-bottom: 0;
            -webkit-text-decoration-skip-ink: none;
            text-decoration-skip-ink: none
        }

        address {
            margin-bottom: 1rem;
            font-style: normal;
            line-height: inherit
        }

        .d-block {
            display: block !important
        }

        .text-success {
            color: #28a745 !important
        }

        .font-weight-bold {
            font-weight: 700 !important
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @foreach ($data as $item)
        <div class="page-break">
            <div class="mt-5 mb-5" style="margin-top: 20px">
                <div class="d-flex justify-content-center row">
                    <div class="col-md-10">
                        <div class="receipt bg-white p-3 rounded">

                            <table class="table" style="margin-top: -15px;width: 100%">
                                <tr>
                                    <td align="left" style="border: none">
                                        @if ($item->seller_id == 380)
                                            <img width="150" style="margin-top: 10px"
                                                src="data:image/jpeg;base64,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"
                                                alt="" srcset="">
                                        @else
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACKCAYAAAAQT3cyAAAAIGNIUk0AAHomAACAhAAA+gAAAIDoAAB1MAAA6mAAADqYAAAXcJy6UTwAAAAGYktHRAD/AP8A/6C9p5MAAAAJcEhZcwAALiMAAC4jAXilP3YAAAAHdElNRQfnCwkEATTy65IpAAAqpklEQVR42u2dd5xcVd3GvzNbsumbQhrZYOhIkSIIKAIiClhQEMsLCgoi9o74KorgqzQBFUV5sYAG8AWVYgOBANIJSSgJKUCA9LqbTTbZNjPvH885uWXnzsydsjO7e57PZz7JzN57zrnnnvvc3/lVcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcHBwcBjwSFSgzUnA+Gpf2CBAB7ACyFR7IA4OtYL6CrT5BeAzQHe1L24AowGYDXwS6Kz2YBwcagWVIKxxwDrgYqCn2hc4AJEBzkWSaiUkYAeHAYtKEFYCWA38BUhV+wIHKN4OHFjtQTg41BoqQVgASdN2NGGd/2D02ZcfU70ZqQ0kqz0AB4daRKUIKxp9iaoBPaBdkcc4AnNwcKAahOWhGTgdOAFoBJYCTwHzgFeBLTuO9BOYIy8HhyGLahHWSOBy4Bw8xfK7gM8C64EliLweB55D5v3tgJO+HByGMKpFWEcDZ9DXCpYEJpvPUcjKuAZYADwBPGn+vwZrgTz/QUdaDg5DBNUirP2B4QUc1wC0mM8JSMpaAcwH7gDuBDocaTk4DA1UyxpVrDPkcGAP4DTgd8ClwAhAklYuy6ODg8OAR7UI6yEkKZWCRuRR/xmgacevjrQcHAYtqkVY84ELgGUlttMA/BC4DW0Z6wBHWg4OgxTVdFCcBbwH+BLwJ2Ax1hIYDyOA9wK3AN/GSluOtBwcBh36n7CCyvEXgZ8ji+FxwAeB7wP/BF4nXixiM/A94PNY66MjLQeHQYXqWAktaXmE0gusNJ97kJQ0HVkTjwAOA/YGdiI3yTYAX0OZDuZW5docHBwqhmp6uvd1RfAIrBN4yXz+CowCZqKA4L0QaS1GBHY2IjOLacCHcITl4DDoUF3CCsNPYMHt3FbgefMJ4yng/5CzqcURyJu+o9qXVBFUeqvrfNocahS1RVh+5HtovIf2ceQFf7Lvr5OB0QxWwhKSwES0DS4F3ebThfWPc7GbDjWK2iWsfLj8GPtg2fAdP0YgPy2WN07o96G1dG/sj25GA78B9qG0vGO9iKjWA4uAR4D/AGsBj7wccTnUAAYuYQURzntex+DP1lmH9Hq7lbHNdwOfQwHnvwRuxqVodqghDBbCyo5hI0HuEgcC6Qr31oUcWNf34xVW4poagEOA64ADgAuBLS5e06EWMIgJKwEjmgE+jHKkVxrtwGP0L2H5sR7lEotbZSeBYjQnADOQRRa0pf4isAlFE6QdaTlUG4OYsHagv8pkVVqCy4c5wKlIJxUHCUROo4D9gK8AJ5nfk2iL+A/TvoNDVTEUCMuPFJIYykUuw4Ex1b4ogzQyQMQlLJCVcCsyXsxH28EPmb9NBk7BEZZDDWCoEdZyVOtvLaUr5VPAR4GLqn1RfVDMts1zZdiA0va8HZUaA3gLg9mvzWHAYKgRVjcy3a8ptSGDlWjLOfAtkp6bCCjGcwEeYe2MtozFE1ahzq5OR1a7qIF7ONQIC0wKmlJ9pYx/1+Aqx+WRVjfQ6vtLAzZ1T6GIXtyNaCtt114GuU5sy3pe4Q7E+a+t1HaiQ8mK6zMfKhnRUMjY4t/D7UCmknUXhiJhOUTBW2jD8aQrkG6sMOfU7Iu8BQWwvwXFgk5Fzr0J0/Z64BXgaRS5sBiriyvccfVAgu4rCeAZ4IUd7US14Y25HrnBTDXtJJFu715kBc6G4SgX2xjT5zKUoLJcGIaMKZMoTfeaADaiAsfbch6Z/R5Ow7uH+5g5Gmna7UWqhGVI1/kY2sl0B9orA3E5wnIQgov0AOCNvu+r0IMbpw3bzidQvrJdyR9G9GlUNfw+4LfI6z61o+3cCz6NLJxv8v02FziTXKTljTmBqjhdhmdI6UUuHXfl6HcsqgC1u/l+G/Aw5bNODwe+ZeayVCwycxtNWH3v4T7Ax1Ho2+6YCJIcOBvpiGejSIyHKGPBGEdYgxGlbSWmA98Bxvt+e4h8+qtgn6NR6uovIt+uQpFAb3JLcr8FrgDW7egjesE/h3Kh3Yjn/X8w8AvgLGx2W38bwTGfAfwIj6zSKFfb5VhJITsyBMmpEm405bJq5x5bcD5GAJ8CvopeNnEwGRmkTgT+APwYvfRKJq3BpYMZ2rB+U3E/DUihvgvwMeBWtNAslpjfCsVE4GfI0hgmqwxyK1lMsO7kKvqSwnjgG8ANZmzZEVz8jyIpa63vt7eb8UwJnBd8OE8BfgKM8/12I7IAF5MFt5LIIAKL+4HCia8ZvSiuoi9ZZZB+01879FlkgOoKHTsW+IKZyz3KcfFOwho8OBilmo77hm9AUsV0pGvyb9tagR8ggomG9/CPRhLJWaEj2oEHgbuRXmkNVkGrLcY4tPU4HklWfoJ6nznuHGB91jd00ML5N7SF+il6YDBtXoEkvrYQWZ2ICG0n329/MW2072i/djAfSSzFOAi3A21Z/xrUX/4QFTX2W787kKR9N9JTrUL3MI3WzDiknzwO3TN/jOs7UWzqWcDKUqQsR1iDB1OAD5SxvTR6w96845f8i+xstJ3z4xG01ZpNdCC1rfZ9F1rYXzbt2GpI7ze/XUgUIQdJ6w8o1Oh/fG2cjgj4AjwdzjFoy7izr6X70DaoWiFW+bAGuJ3KRXCcjnSJfrJ6Ct3DfxOt/9oALEUvjOvQ9vwcbBk+kdY3kdRcjHMz4AjLIRpJJJk8bD75lNa7orey3/3hL4hogiXdshGf2skAC5EktAzl97eEczbwZ2Be5BvaI600cC3anp6Pl73js4i0LgYOReQ409fCk6bv13OOtbpIoOe2p2xj8+7hzmgL51es/xORT7DCVfQ9BL18vo4yBv8YWRNByvvbgEeLlbIcYQ0edOA5ssZBHTKdj0JbQz/hvAX4PSKL2UAupemJwJ6+73PR29Qjq1wLNCghdQNXI73Hp8xvU5CuaV7Oqwn6kv0ISVo2+L3ejGk0cDTahlosQA/mooLGW13oHpffD+w4FE9q8SKqkeCRVeH3sBdJWnuglwBIL3ka0jUWBUdYgwePIxN+XHG7HuktxiPCOR4FP1udzkyk/zkVeC1HG0f5vqeQ9FLYQg8fo0XfZdp4L55P2NGIbLYUeG1bkcVzghk/aIvy1dBxyxBZPRNrvNVBI7LCRVWUSiM9VTE6rqPwXlgZ4NfEJfDgPewFfoVUFS3miLehtbapmIt3hDV40IWsY8VmH30ZOW7eihbulSgvFubfs1EZtWwYSdCatBx4YMe34h/+hcgCdbz5vgsi0tyEFXzTb0BSQjOSIMJYhbatDwXOr128Bc1tNknaOoeeifRJcTCcoCVvDdJZlTonS5GS3hLWdOR06gjLwShKSwt+TiGL3heRcnea+f0DSOLJFofZgKenAEli64oei0c425EexBLWaLx8XYW2AdJJ/RARb3PoyP9Fli/vvNrGSIJb7zA2oC1+XNQTvIcrkRNvqfewhyB5jkT3sSg4PywH4fJjwgvzCYIe3rsSLKeWC9uJVwQ3F8K+PcUEmjcjqWNslr+djEJ6hIFffDdJeYLxt5PbYTYO/NbhRCnjcxKWQxDemzGDX6cj3U9LxFk2F5dFM9pilLrg6wiWb7PVffIj6Fd0ESKsbA/Kgci14UwkzdU61iJ9ZdSWsA3YXES7aYL3ayy65+VIKeR32i38HmbBUCSsFJStmk7YczgTp+1+qq5TCtrxgoATRMcCbkNbCBvH9wZEbpuLMl97ZDOZYGzgGqSjKfT8BuTW8Dk8skqhYOY34W13j0TOo+cAq2q8UtB8FPaSS4ItJpSnk6D7SQsyuKwv8R5OwNOFgvzb1hV78UNtS9iIAjh3QwrGUj67ojdHItR+nfk36lNqHcHKIrglmo63RtJEh6l0EpTGpgAfjGgzTv8nE9TXPEM+wgoGM5+HPNb9c34zco68gKAkciIyNIzP0lYtoZDQnGLQSzCr7Dg8y2op9/Akgq4S8wmGTsXCUJOwWpAzY4rS9/kZPC9ekDL4Z0gqiWo7iUzoXyc6XUmtYBTwLt/3LYSdB4O4Bynqm833c5Hy/j9AYUGvwYV+uJknu0a3A3eS64HsG8x8MdoSWtyNJK5WYBZyLP0RnnPqR9GW6psMhOyq5ZcA70PSj3UjOQtZJO8BCksTE7wHB6IXgzUC9KB7WLR+c6gRVh3BmLFyoh49ZPmwiEpLWaVLBg3I4/lY32/P4/fJsfB0XnNQWMYZ5i/TkW7oy/idTvMjgdwPriIYj3Y/fleJ3Nf8PhTT2Oz77SEzFmvlTJvxTURSmPWGPxcvhrK7pOwCxdyHOH2Vcp/9/Xj38Hn0Qj/P/GUSegl/BfgXhTqsCkehe+hPU/Qf5DlfNIbalrAWUEqV5lxIlPBpwCv19WbkZX4hXohGL3ATUYGzQhfaUr3s+21/JMlcjLzKc70gh6E38uVo27a/72+vI0lIOblyZ/58Owp89it65yICDkuI1hv+Bt9vdchv64vY5yMeMRR7DyrdfiH99ALXIK9/iz1RtoVL0dYu18u20RzzPygQ/82+v60xvyuTrQt+LghdyCekXCb3QjGVcHqT8uMgRA7FBMUOR9uiiSglTNhqcBdagPnwLNpOXYdn3ZuKyO8s5CoxB3gVbYmTSAraHcX2HUrQKgha6F9FlrG+CJLJQUhq8scHLkFe7C/s+CXojd2B5w1vKwU1oTjGNpSELk4ep8OBW2LMfRKFG11BYevyTcAfkYQYV62RQZ7nD+Y4ZjEKX7oezyq8E9pKn4F3D5eZ+Umge7gr3j2cFmpzA1oXD1AihhphLUexTKvov8IRaeQh/o0K9zPVXFu58QBarNGpVoJOmn9FUuTlKN2IRYv5nIbe5DZ0JFe++IVou/a3QF/ZsQcKePYreFcCX0IPWd/zvXFvRFLVODxv+NEocLcVbZMKJS17nXEwDm2fCiGsKZR2n+8lG2EF7+G/kMX0SoKS7jQUz3kKusd2vLnu4UvAf6OgZ6+vIjHUtoRp9OC1IwtRf3y2EJ1WpVRU8v61Iy/wTxLc5mVHcBHehSxMN5J9G1mPpJgmsi/0DSiO7RRykZX3gLUgyepI318tCd0TeX4Qy9E20G8p2wltL9+V4zybOLEU5JOKy3mfo/sKzs+9SOK8nuxhNHXkvoetwO+QtbgsZAWDQ8JKELTWFXJ8v/hAlcnXKwopFAIzkvLoxTJoe7QWZQH9J4qq95z88i224Ft6AVJg/x74MApc3gWv+IQfaUTsryCJ7nZEHL2BtrOjCSmJd8cj1l6UQfT/8p7ft7zZF5G0M9nMSZ35bSnZraS9aIubpDiXgjpyZ9lIIx3eGEq/zxnyWaeD82G30zehe3gs2m7b4hPhtreYuXgQ3cMn8TujuiIUgET33UpuZeBhC9ILlfMediMn0L7+VoUutqB+qBst3ofQVmYvtHWbimfq7kDSzVK0fdgQs+9elHv9Wt9v6UA78Yj2CZQhwh+PZz3Is2ETSi1dyn3oIjoqYCuScstlWW7Le0TfjAuPms9kpITfE20PrTvINuR0+hK6j+si2ywRA5ewvAW2P0HT6VBBmv7IilnsQguSQAYF0q4mt8K3mL57yVUYNw7ReuONk0mg0vchSL79ieCcgKTvtVjfukLbKCMGJmF5k9iI0rmOK7qtgYg8iyDuVrRi2+NKh7aUu/247Q206xuoY/BhYBFWkO0TiKw+XO1h1QIiSGoECjUZg7el6EV6jFaMX1P43AEQ4+gwRDFwCCtIVk2o7t1FBEMvQihvnv4SlOjlqitXyJjGoCRv70A+MS1IArWE1YOsl6uRYvwRVKn3VXzB2460HGoRA4OwgmTVghwRP0HORGUZPvj4rZCvLHd87In0ZoWyYZoK6dhCZDUKmZA/hYhqZI5Td0JWtaOQlPoqsgr+AVnn0rZtR1wOtYTaJ6y+YRc/JuhvE4mZbauhfEnILE42Y4gjNZXdXypEVvsj59T307eUeApt/brMOIahraL1nalDVtYvoO31jcjqttz240jLoVZQu4QVJKoGZNr9Pn3d/iORqYwzexI95HWlNlQsQmT1ThSg6q8A04sCWR9ANeVeR+4DdUgSa0EhHkeg+D1bnn0SCqE4Gvi2Od+RlkPNoHYJy8MoFOv1ZbLrqx5HD+M7qz3Q/kCIrN6BPJH9sXPzkU/S38idd+gWRFSHoPxQH8TLBXUY2h5egAKRKxWw7eAQC7UZmuNJV00ozcf59CWrThRl/xEkRQw17IE8si1ZpdF27oMoYHcHWbV0bwx8fGhHqV/OQ2EY/8bTzU0D3kNxBQ0cHCqC2pOwglvB85BuJUysy4FLUMhAF7VKvGWGT7pqRNKPTR+cRhkSvo2vBFbUNs7+7muvFxHXc3ipVR4hWNa9mHHmhNtmOsRF7RGWh8ORZBVWIs9F28NHqj3AKuIYghH7dwDfpQCy8iMLcW1EriIPobi65YUOKAdJJfD0fSl81lX/OYWSVz4yzNdOrvOjzi1nTGiu8RUztkqMMVt//TUH+VCrhNWI0oJMDf3+FDLbL4jd4uBBPfBxvNpuVtpsg+IWQ4i4elCkfp+/Z0OWhdyArI4Ho1QvLWasCbQFfQ1JcnNRMHGgKEiM8U9D6yOBnGDzZ5TwsDPe2tqIzwctB+qQRNtcwLGYY7YhC+1aFO6TKeA6EyhIfKL5voZgcYhc2BUVACnGATGBdivziJaqG1DOsVEx5qADvUjXmTko5l7vQG0RlrcdPBh4d+ivy1Gq1iFJVj5imImseBY3I0V7yWjp3hjL6z10bCMyAnwCuZ9MIdqS2osewn+jFCRPEP8h+yTaAidQDq5PULirybl4+cluNd/zGRaGofxQRxZwrEUKPfyrkHPuTRh9aw7LawLljzrdfL8aSc+F4Ey0jY9bph6kVlmFinEsiThmFDLoHBBjDnrxgqMfRsac+XnmIBK1RVge3oW/eolwHf6sk30DM/sL1daXHYQnHWzCJpejPDqhIrdmM5Al92MUVtW3HkkCn0Z+bb9Aeac2x3j7NuE5x8ZJL2TPtecML/CchDkvrhFiNMpycBDK7/VdRNKZiAc2YcYUd3wQrM5UDLKljck2trhzMAa9wN6M8qSdjz/1TwzUImE10dcx9FWUX0foG5AZnuQ6IJFJVMQP6zUUrR7HcXQmeqjLgf3w7tsSlNK2XxEiqz1R2t1jQ4e1o/xWy/CyDeyEiGpXgr5f3zdz9HXiZUqwKG8MVmHYgtLwRC0ym0trBB7pTEV5zRegXFHlvjb/sV3Eq8yUQNu2OC4s7chan28ORuKlotkF5YdfACyIK2XVImGNNxflx7OItKKix1sRkXQgz/blQOekjtZKjO92giXc8yGNHsjzy9B3giDxvYRZlFWyuE1CkpGfrNahbdafUYrjNrwtSj3SAb0RuVF8FJFYEuX22oS2ND0DwFn1p+Y6czkQ16E4zqNQIrydkKRxCoUTVrF4EG2ZCyWgBNJfFmpoSSHy/XueOahHz/RxyOrfjF5O76cI9U4tEtYY+qaLWUjufNfXo8XTveOTSGw9beFsUIrcw1F8XTm2c7aPuOeUAwmkR7BYR3WkC4tPAyf4vj+FiPnhiHH1ImnrYSSl3ol8yQ4wfz8Xedf/vYrXVCiWU/gD9wAiq8+b7/uibVXRJdsLQCvSFVVqfWSQEFHoHMxGpd9sGbh9EdHFckquRcIaSXAPniZq22Olrb2PbaWuoZVtbdDVAT3bYdNy9kn18EjDuNk7J5JzEWHtR3T14ryI+8avcIpkqC5ZteAtPtBL5RwUEuQdlGXOzLxkUK3B85Dh4A2IjM82v1cqD365kIy6vtB1WvijDoZR+dAum2s+JyGUKMXGmYMUwUSHwwsZXxi1SFjh/XAbuVm8iUWzpyH2nohMr+3A6l5YcURP68ZDEvWbr6wfdd+eibr7BniMiTWVW0w081UN4joULzV1D8qhvoOsci3ikDXycVRw4sfm+xGm3Vq3BgfcMfJgKrKgWmyh8qXmkuhZyFaPMEPIJ64f5mAm8Dbf980UYc2sRcIKYwHWzBrUX81AFX5PRJLTRCSZJcxEdACvZeCxOZnevx7T0/ZYA3ROSST7vShhGZFBBQssdkNSyZbimisJ++Ll2HoFX3WaQp1WfQv9H6j24CRUH3BPap+wDkK533NJSsPROn0v8Fbf709SecI6HNUvDJNSAm3Lf4ByohWLJIo53Z5nDkbg6awONb9lzBzEJsyBQFh3AO1cdrQq3clM/HHgc0h5m81CYctITUA+XZ8A/tUDVyzPpOcU0GctYyHaJidRUYfdgXlVUFKP9f3/ZYy4H2cMPtJaiR6eSYgEd+rPCykSn0E6t1zIVgLsefxlryqHXPUR1yDdYamE9TX0ook7B08CdxfTaa0T1kLgLySTIBeFmai0+Idijn0MyvV0BHqz3IgRR/tBz1RuzEX6kKnowX4/8k7ub/hfFFspLatqiqACur+K3JaCYgw4z6KH/JVqD76Kc/AkIrlVEF+HVquElcAG9NbVv8plx4C2Cf+LvKiLRQsyR48DrgF6R5FgK5k6pAithC4oTRlKNPmkkZdQySVbVv0M5IT3YqlZQqPIO6I9v/FiEtqO98aR9Hz9jcWTqmyNwjgoiODK/HJagyxx/r4zSLKfgbdN6kRxr/ciyepVe3CFJeKFyP0m/CJJIP1RqZ3bSkibs8zBCDMH9vcOZBn+F3J3WVl4N0HUKmHVITPoLO75CaR7JyOiKYWsLEaiAN9W4DcrSTOWxDTkzDaJyuRf372MbXUh3cRJaGHsjrynz8M86MVsD0MP8z7A3mjBpyLaW4q3Nd0b6dOez99T1v4OR/F9IINJnLhAEEnUU7j7SBzv8ShcgzzW/c9QBukUf44XWpZC6X5u9Z/cD9v355AfVqWQBn6ICCg8B+ORM7FVsvegkJ5/lDoHtUhYNkj2Wjo2tjLvzjrkAX1Cac0GMBKlFH72xJ62OY82jFuT0KQOlCSA9yIfpo+Z7x9B28Tv4auEU8iCyCJ1HIYymO6F0sz8MeLUZ/C2plNQHNv5mHzwMcz9OyF9kHVlWUh0LJsffglvDJKQu6P69vWZJOjn10VxknUb2QqGCt9D+tUWtNa+hXzUXoF+c/JNoOe7t0L9ZZCjb7Y5WI2cpW9F97cZxUfOp8itoEW14+KixnQvcC+3XwCZ9NuQb04h6EJm/0KkpBnA11dk0k3LMqke9GB29MP1Fe1/47vJ25Eub5GvzS+hmLwdVbCXN07Y8fEj4vfhyNv8FlR1pxm4zPw/GxYTzOrwaeSHlYzqI0uf45G39DHmexot8kJCFJbj3eddzSdrP6E+p+A5qoIeoGKk6oS9J1kSIz6FTETWEnggirVsyjKeisN/Lwr9lGEOHkC7Iju3b0Xpt+tLmYNalLBagevZvGYb616qR2Q1Ps85C1DIzJOIdGaiLdNJBD3DwzgpA0ec27tl9n0NzY+iajyHUDm/piR60Mrh4fwCUuBej3zQ6pA19BAUKH4XvjCLHAtkDNqSnYsyjDb5/vY40alNrJj/DiRJjAGuQHP/a3y6mix9NyEDyDeR5Gx1HfcjJ9JC8CyyTE5GUt4PCCm0s/Q7EW2f9zPfO1GmiLIg5KrxO/SQftR8/y+UseE3dmz9IGn1u39eaA5+ieKCTzLfzzFzcFuxc1CLhPUy8BI3nQuZzJ4EHe7CyKAFfuHE4cOWHdLYyOjOXhYNSz78QnvHLShm61L6xiZajAE+cFyycfZLmdT23RN1V9enU4n6dCq2lSqdSNBdV5BuPUMJCym0IP6JtlPXoJTJIP+on6FMrQ8h0lmKXgRd6J6PRhLmQShVzcEEib0TGTguJneZ9Dlo+3MNUpyPQbGApyDp63EU/LwFGR4moa3SsSi+rtnX1gto67TRXmee61+EdCKfNH86Genz/ooeihVI2q5H7i2HIEvxkXg7iwfNHJWNPHzj6zDzdyDS8TWhbdFcjFW3wqR1EHLmjSM9JtCL/9EyzUEr2hrui57BUeb7cxQZtF+LhJUiWQ/b20GOZlNzHHsv8JXDmkdveGLxVcm1J1w9NfV6a2PjsXttuv/Opzd/PNN5a29vqhflfh8b0cZbf5naPv6Xqe2bpn7uz7QPG5lJptOxCSWdTJJKJBm3fQurrjuVFRUU+7M4Xa5BhoQTEDFYRfjeaKvWgVfqqwHpVUaS3Xq5GG1nZhEhCYb6v8n0dwleRaM9zeeziDQ68TIXNGVp8mEkHcVxz+hGL6N9kd4N8/99TX9b8AhrDH3T3ixCD0+cjAZx8SJSTF9vrn1X0+eZyLpWSdh7EBfWCl0uzEGqhWuQnnJfJOV+Btg2sLM19M3EcADReratwNW7jRy+4W/vOXL4miMu/Xp6U8dZma7e4T2PvLz42IN2+96suS8/8hE67kCexmdGtDMDmM7kPTatHjVhBNLjzCC+FJQCFq4ePeEfifMfbIu4nrIhRBpzkXvDqSgj65vxiCGJHtZcearSSBK6HW1Zlvr7ydN/GvgtIrpvIsOFzVNVl6fvV5Hu8Jf4nBhjLOAl6H5dQnA720R2YgRtZR9AkmHc4iV+/WOkFB66N7chqe5z5vvJSPrNVduyLuL/ccZXLPLtLmwf9THm4Ea0PbZJCU9Hc//zuIOrLcLqO7aWHH9fBDx9SVeS3rnLTk2t3XIh3alGEpDetG0aGS4/9sSDTj7y0efWP7ap3VrUsiU2Gw1MYkSz/f+XkIWsGKTQVuqr9EPwbmhRtCO9yR1ocRyPiGsGki6H4fm39SAJZC3aij1oPi+H24/R/6PICvQ2FDJ1BNKtjUb3Mo0ktg2I3O5HpcgWx+kzS78vItI6HhH2wUivNRKRdRpJWmvN+O5CW+m2OH0iR+O/mzlKkWdL4xtjN5Iw0sg6mTD/TiS7hS2DJE6LpwsZnMELZoylhMzmcinpQtbpeWY+lhU4B9vQ9ngr2hYmkPGjGd99KAS1TFi26GcU1gLtH+vZmli92x7H0iOyskhv6XxjzzOvTT+8h/WPyRK0neyEpQRjmR0CVSk3uw4pGC/Dp3SuJLIUkmhFRPA3tBWajB6OUcgSuM3MhTVJt4WvucjwGtDW8x7zmYC28xMReXQjklyN7l1nuJ241+3rdyvSXd2NzOjT0MMw3PTTitbAekIBtzH67UIPXTFjfB25iFjkkmLSyIfpV7EmRLgZ+BOlKdtzrf8OYvp2+eZgCfIVLGQOIlHLhGXfyFFoBOquTSR6T911t40kEuwgnQwk6pOdyQkjO9u6toK2B1HicobgIi41LOQVYr41yoEsxAWSutrxbfEKaaNMfW+kQG/qMvbbiwgxb4xclZMDVsp6l2LgFL0tag5qmbB6iHbMAykwp66eNPHVRHPTrETb9vdmOrr3IZOBxrp0csLIWU0fOOTle6/6M/S1gvnRBWwikQSR5CbzKcS60osWiCW5pUih2FatSavmg1itvivdbyntx5VWh/oc5EMtExbklgxmAh+4fFPrNYeu2/DsEYe96fTUitbTMu2do5PTm5+t33fqn8769T+7V3R0TsHzhcmG9cAqNr4G2jp8imiFbRidBNOEtFJFsnJwGOyodcJ6Gk9RF0YS+Fpvb+r50+rq7r/o5RXz3rOxfd4oGlm2tJPzV63k2faOUUjiOTRHHwuANbSvBUlMhYSF5EcFLYQODg7lw7XAfcQvBZQN45ATYCbHZxny9/HXwRuBfHNuQRJQ1Llpc26t4WpkRStHkK6Dw6BBrUtYrcjqcTjRyvA3IM/uzyJpaRsy5b+J/IngrLe0g4PDAECtExaIsD6KSCvXdexvPoUijXymXqv2BTo4OBSGWszWEMYaFN5Q7iKD/wB+X+2Lc3BwKBwDgbBA5PJDyuc9/gwKtK1IpVUHB4fKYKAQVgYp8y+i9GDVJ1BA8MJqX5SDg0M8DBTCAoV2XInI5oUizt+OgjBPpzpFGxwcHErEQFC6+5FCBRfmoIDXD6MMm7muYzMKzL0BBb3WekXhOHgjyoV+H9WtAu3g0C8YaIRl8QpKD3IDykxwOEreNg7FGG5Bwa7PoYol8+mf9Mf9jcNQYY77qj0QB4f+wEAlLIvXzecW5KjahLa53WgLWIkKOLUE6/wahQRK7xJ24rWl0pvRfGV8x2/FK/CQxEt8uIXo0uIjUDJAf1K6MUgi7ggd588Y4Ue9+fsW33hs0Hqul80I87F+ej1mHLaNYWZsW0PnZEJjSJhrtXPgD7kKj81mEmn39dNo+vKXKLMZMvzYFrqesaa9rfSt+lPo/NtjxxBMythh+ouaK9COY6vpp4HgWugy/Ub9zeqTR9M3nM32Xee7Rkwb20LjKhgDnbD86KI8udIHE5pQhd+98R7OBMoo+iJKoTsRLSj7QPwKJfLbBWUB3Qstso3m92y17t6I0v9egvSD+yCr7jXAf5AD79kokV0TWsz3oWyl9gHfGyW2+6bvt/eZ3y/JcY2fRaoBvzHm78hI04vSMR+NQrRSvnM2ofxhoG3111A2TFsN5k5UwiqF1A7fMJ/NKH3Nb5BbjM1B/3ZU2uvbpt8EShd9HB5Z1gF/MP1OAL6MwsZsrcB7UELDbuQQ/TWUNTSD8ojdjlLoZHtJTUCZTcfjEd9WlOrI5q0Pz1W9uZ+/QgatAxDJ9qBnyRaSiPrbj/CyzR6GR8RJ0+ZtZq5+a/rq9Y3rV+Z6Y2EwEZZDXyTRgvk1WrQ2ffIKRGAXogV+pTlmPqbkPCKPGegBTKMFOTmin3nm3ItQSbbvo1S7T6A3+qWmn1+bvt+Ail5MNcemkCQyk2AaoPF49QqjMA0lH7zeXNteiDzvRGFbY+mbCHIqQYPTuYhkv4MeyENQ/nkriYTH1mj6+T4qCvoQkm5afOckUALDOxDxNZjz7fx+BJHptxCB7G/Ot318yVz7BYiw3pJj/u2YJqAqRLYQx3nAx/EIa5oZ6/W+8WxCBPQzc68uRPG0s/CkJP/fFiOS7jDrot5c5yxEQI1mbteYPpvMud/BS/vzbpSB9WH6Sto54QhraGAa0vEtIZhRcjES5zejzBgv+v7WgB7UGeiNbesFZnu7p1D847XAX1DI02Xo4T8QSQmno+07qOLNKyhFbgtessNw24UU7Eiba3sHIok3mN+7Qu2Ez/H/1oBXsbnTzMVSPIkgvPVOIEJ+ElXrOdscGx5vBhHfHoionw/1OQyRUjPSuf4H7wGu981/Dwo7i5p/21c9IrZdzBj3JOi+k0Yvgd2RxDbH9zdLcuvMWBdl+dv6LH+zfU821/kqQSu+nRN7vfblVFQxFkdYgx/1KAvqm1HNv3AK3CRa3GEXl58iKcvm4R6DHubPk73U+Ba0xbwZbUM3md+HoS3AptDxbWjBhrPA+h/IQhZ0BgW+72euY5TpcwqmaCfZicSvx7kOSRu2MO0oc8wXyJ4G2KaatlLdpcC/yU4mRyMCuZ8gYc1CRqLTzPfhSBL5KjIW/dTM9Rm++d9ufltFdjQgkpro+z7VzIctGHsYIsinkVQc1ovZ9ZANiSx/s3N5PFIN3E3wxWdJ+7voxbePGf93KMJiXynCyqcMdsiNOG+eJHro03iLKYOnNO5C9QIf8h2bLzPlMLTluQGRU4/5/nskwayMOG89Evv9pcGWmHGdjfQ3HWjr8hXTjq2d2IZSKe+H3vzDkXSWq8yYvf47zDXWmzZmobf9XDOmnZEe6jUkYeyFJ3k0oIfoJjOWHrT9+6M5JypvuVU8/wgV0Yiq+nOdabvOd5/SaAt4J9LzdSEJ5Y/m+heY8V+PHu4eM8bfIfJbFTGedrQlXGLWwNFIAhxj5iFp5uYqM1dJgrqlYpBAa+knSB8WXmMJc5+/YMb9DXONTxbTWSUIK4MUdL9g4KRrrSVkUCXkNQUc24vq+/2BoAVnI15UQB3S6ZyNJ0ndhvzZLFIESbIeuYucj7aL3eitPYfcEQJW8vBjHVLKfgtVtmlHpLECPUx2C7QMKcuvQKQ30lzfLwqYg9OR3imBpJRGFH4Fqs7yAlKSrzV9rwT+Zf5eh7ZRx5qxdZljXkBbV3tP/GvZfrck8R0kvYal1BTSjx1vjq0DZiMi2h9V0OkwczDWjO9R9MI4Ksv8P0VQevEjjbb3V5lzbKGHR/FC0HqRFHmQbzzPIB2mfcGliBY2wltpfN+/AnzI1+7f8Ywa3UjCbkXEdgMq83UlMbeFpeYvz4ZzUfUSh+KRQIvzYvqauv2YhhZfOF/9dpRHbDuyQk0Otb0Ir+pLvWljCUG3hDr0Np+ByGM9IqutRKMJvawW0NcVYSzaMoxDb9pF9N0S1CPpZzpS9i6g71YyjL2RNGLXstW3ve47psn0Pdm0tyB0HUlznbbY50ZzjLVWjjH9zEMP9khkUXzOdw27mWuch7dN2h9Pn2Tn/nVzTAJJfjPNea1m3JZc7Pzvgkh4fcS8WgxDxNvs+20T2vbZaw3PVQK9GJ/GI6l9zXW/nqWPbH9LIkl4uu+3BFI9vBAxV7ugtfs0MaW7ShBWPeWpjzbUYctxOTg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4OAwJ/D/b/3b2vFldAAAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAyMy0xMS0wOVQwNDowMTozOCswMDowMMgBNl4AAAAldEVYdGRhdGU6bW9kaWZ5ADIwMjMtMTEtMDlUMDQ6MDE6MzgrMDA6MDC5XI7iAAAAKHRFWHRkYXRlOnRpbWVzdGFtcAAyMDIzLTExLTA5VDA0OjAxOjUyKzAwOjAwjFb59AAAAABJRU5ErkJggg=="
                                                width="150" style="margin-top: 10px">
                                        @endif

                                    </td>
                                    <td align="center" style="border: none">
                                        <div style="text-align: left">
                                            <h4 class="mt-2 mb-3">Shipped From</h4>
                                            @if ($item->seller_id == 380)
                                                <p class="fs-12 text-black-50">Shipbox</p>
                                            @else
                                                <p class="fs-12 text-black-50">{{ $company->name }}</p>
                                            @endif
                                            {{-- <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                            <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                            <address class="fs-12 text-black-50">{{ $company->address }}</address> --}}

                                            @if ($ou->ou_phone)
                                                <address class="fs-12 text-black-50">{{ $ou->ou_phone }}</address>
                                            @else
                                                <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                                            @endif


                                            @if ($item->seller_id !== 380)
                                                @if ($ou->email)
                                                    <address class="fs-12 text-black-50">{{ $ou->email }}</address>
                                                @else
                                                    <address class="fs-12 text-black-50">{{ $company->email }}</address>
                                                @endif
                                            @endif


                                            @if ($ou->address)
                                                <address class="fs-12 text-black-50">{{ $ou->address }}</address>
                                            @else
                                                <address class="fs-12 text-black-50">{{ $company->address }}</address>
                                            @endif
                                        </div>
                                    </td>
                                    <td align="right" style="text-align: left;border: none">

                                        <div style="">
                                            <h4 class="mt-2 mb-3">Ship To</h4>
                                            <p class="fs-12 text-black-50">{{ $item->client->name }}</p>
                                            <address class="fs-12 text-black-50">{{ $item->client->phone }} /
                                                {{ $item->client->alt_phone }}</address>
                                            <address class="fs-12 text-black-50">{{ $item->client->email }}</address>
                                            <address class="fs-12 text-black-50">{{ $item->client->address }}</address>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                            <table class="table bg-white table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Order date</th>
                                        <th scope="col">Order number</th>
                                        <th scope="col">Payment method</th>
                                        <th scope="col">Shipping Address</th>
                                        <th scope="col">City</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->order_no }}</td>
                                        <td>COD</td>
                                        <td>{{ $item->client->address }}</td>
                                        <td>{{ $item->client->city }}</td>
                                    </tr>
                                </tbody>
                            </table>


                            <table class="table bg-white table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Product name</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Mode of service</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->products as $product)
                                        <tr>
                                            <td> {{ $product->product_name }} </td>
                                            <td> {{ $product->pivot->quantity }} </td>
                                            <td> Delivery service </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <table class="table">
                                <tr>
                                    <td align="left">

                                        <img src="{{ $item->barcode }}" alt=""
                                            style="width: 400px;height: 80px;"><br>
                                        <b>{{ $item->order_no }}</b>

                                    </td>
                                    <td align="right">
                                        <div style="d-block font-weight-bold; color:black">COD Amount:
                                            <b>{{ $item->total_price }}</b>
                                        </div>


                                        @if ($item->seller_id == 287)
                                            <div style="d-block font-weight-bold; color:black">Shipping Charges:
                                                <b>{{ $item->shipping_charges }}</b>
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            </table>

                            <span class="d-block">Expected delivery date</span><span
                                class="font-weight-bold text-success">
                                {{-- {{($item->delivery_date)->format('y')}} --}}
                                {{ Carbon\Carbon::parse($item->delivery_date)->format('D d M Y') }}
                                <br>
                                @if ($item->agent)
                                    <small>Served by: {{ $item->agent->agent_name }}</small>
                                @endif
                            </span><span class="d-block mt-3 text-black-50 fs-15">
                                {{ $item->customer_notes }}</span>
                            <hr>

                            @if ($ou->id == 3)
                                <div class="d-flex justify-content-between align-items-center footer">
                                    <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                                        <table class="table">
                                            <tr>
                                                <td align="left">
                                                    <h2>MomoPay</h2>
                                                    <small>1. Press *165*3#</small><br>
                                                    <small>2. Input merchant code 659130</small><br>
                                                    <small>3. Enter Amount UGX {{ $item->total_price }}</small><br>
                                                    <small>4. Choose mobile money</small><br>
                                                    <small>5. Input your PIN number to complete payment.</small><br>

                                                </td>
                                                <td align="right">
                                                    <h2>Airtel Money</h2>
                                                    <small>1. Press *185*9#</small><br>
                                                    <small>2. Input Merchant ID 4342744</small><br>
                                                    <small>3. Enter Amount UGX {{ $item->total_price }}</small><br>
                                                </td>
                                            </tr>
                                        </table>


                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between align-items-center footer">
                                    <div class="thanks"><span class="d-block font-weight-bold">Terms&Conditions</span>
                                        <span class="" style="font-size: 16px;">
                                            {{-- <p>Payment is to be made on delivery via MPESA PAYBILL NO. <b>4032407</b>. Account No: <b>{{ $item->order_no }}</b></p> --}}

                                            @php
                                                // Replace placeholders with actual order number and paybill number
                                                // $waybill_terms = str_replace('{% paybill %}', 4032407, $waybill_terms);
                                                $text = str_replace('{% order_no %}', $item->order_no, $waybill_terms);
                                            @endphp

                                            <strong> {{ $text }}</strong></span><br>
                                        <small>{{ $company->notes }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <div id="packing">
        <div class="information">
            <table width="100%">
                <tr>
                    <td align="left">
                        {{--  <strong style="line-height: 20px; font-size: 15px;">{{ env('APP_NAME') }}</strong><br>
                        {{ env('APP_URL') }} <br>
                        020-900-939-333 <br>
                        support@logixsaas.com
                        <br> --}}
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" style="width:200px;">
                    </td>
                    <td align="right" style="width: 40%;">
                        <h3>Packing List</h3> <br>
                        {{-- <small>{{ $company->name }}</small> <br>
                        <small>{{ $company->phone }}</small> <br>
                        <address>{{ $company->email }}</address> <br>
                        <address>{{ $company->address }}</address>
                        </pre> --}}



                        @if ($ou->ou_phone)
                            <address class="fs-12 text-black-50">{{ $ou->ou_phone }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->phone }}</address>
                        @endif
                        @if ($ou->email)
                            <address class="fs-12 text-black-50">{{ $ou->email }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->email }}</address>
                        @endif
                        @if ($ou->address)
                            <address class="fs-12 text-black-50">{{ $ou->address }}</address>
                        @else
                            <address class="fs-12 text-black-50">{{ $company->address }}</address>
                        @endif
                        <br>
                        #Date <b>{{ date('Y-m-d') }}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="invoice">
            <hr style="color: rgba(0,0,0,.12)" />
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $order)
                        {{-- {{ dd($order) }} --}}
                        @foreach ($order->products as $product)
                            {{-- {{ $key += 1 }} --}}
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $order->order_no }}</td>

                            </tr>
                            {{-- {{ $key_ += 1 }} --}}
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>



        <div class="invoice">
            <hr style="color: rgba(0,0,0,.12)" />
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Merchant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pack_list as $merchant_id => $merchant)
                        @foreach ($merchant['products'] as $product_id => $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>{{ $merchant['name'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="information" style="position: absolute; bottom: 0;">
       
        <div class="information" style="position: absolute; bottom: 0;">
            <table width="100%" class="table table-striped">
                <tr>
                    <td align="left" style="width: 50%;">
                        &copy; {{ date('Y') }} {{ $company->name }} - All rights reserved.
                    </td>
                    <td align="right" style="width: 50%;">
                        {{-- <a href="https://logixsaas.com" style="color: #212529" target="_blank">Logixsaas.com</a> --}}
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

</html>

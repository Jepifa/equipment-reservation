/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      spacing: {
        "1/8": "12.5%",
        "2/8": "25%",
        "3/8": "37.5%",
        "4/8": "50%",
        "5/8": "62.5%",
        "6/8": "75%",
        "7/8": "87.5%",
      },
      height: {
        // Half hour height for calendar week view
        hhh: "3rem",
        "2hhh": "6rem",
        "3hhh": "9rem",
        "4hhh": "12rem",
        "5hhh": "15rem",
        "6hhh": "18rem",
        "7hhh": "21rem",
        "8hhh": "24rem",
        "9hhh": "27rem",
        "10hhh": "30rem",
        "11hhh": "33rem",
        "12hhh": "36rem",
        "13hhh": "39rem",
        "14hhh": "42rem",
        "15hhh": "45rem",
        "16hhh": "48rem",
        "17hhh": "51rem",
        "18hhh": "54rem",
        "19hhh": "57rem",
        "20hhh": "60rem",
        "21hhh": "63rem",
        "22hhh": "66rem",
        "23hhh": "69rem",
        "24hhh": "72rem",
      },
    },
  },
  // eslint-disable-next-line no-undef
  plugins: [require("tailwind-scrollbar-hide")],
};

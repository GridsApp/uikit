import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.php',
    './js/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
        colors: {
            twafieldsprimary: colors.blue,
            twafieldssecondary: colors.slate,
            twafieldsdark: colors.slate,
            twafieldsgray: {
                50: "#f9fafb",
                100: "#f3f4f6",
                200: "#e5e7eb",
                300: "#d1d5db",
                400: "#9ca3af",
                500: "#6b7280",
                600: "#4b5563",
                700: "#4b5675",
                800: "#1f2937",
                900: "#111827",
                950: "#030712",
            },
            twafieldsblack: {
                DEFAULT: colors.black,
                50: "#f6f6f6",
                100: "#e7e7e7",
                200: "#d1d1d1",
                300: "#b0b0b0",
                400: "#888888",
                500: "#6d6d6d",
                600: "#5d5d5d",
                700: "#4f4f4f",
                800: "#454545",
                900: "#3d3d3d",
                950: "#000000",
            },
        },
        keyframes: {
            progress: {
                "0%": { width: "0%" },
                "100%": { width: "100%" },
            },
        },
        animation: {
            progress: "progress 2s linear infinite",
        },
    },
  },
  plugins: [
    forms,
  ],
};

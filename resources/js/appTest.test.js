import React from "react";
import { render, screen } from "@testing-library/react";
import MainLayOut from "./Layouts/MainLayOut";
import ApplicationLogo from "./Components/ApplicationLogo";

test("initial test", () => {
    expect(true).toBe(true)
})

test("Guest not seeing add idea form", () => {
    render(<MainLayOut />)
    expect(screen.getByText(/Log In To Add an Idea/i)).toBeInTheDocument()
})
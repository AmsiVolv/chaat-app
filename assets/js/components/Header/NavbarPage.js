import React, { Component } from "react";
import { Navbar, Nav } from "react-bootstrap";
import { Link } from "react-router-dom";

class NavbarPage extends Component {
  render() {
    return (
      <Navbar bg="light" variant="light">
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mr-auto">
            <Nav.Link href="/">Messenger</Nav.Link>
            <Nav.Link as={Link} to="/bot">
              Bot
            </Nav.Link>
            <Nav.Link as={Link} to="/about">
              O aplikaci
            </Nav.Link>
          </Nav>
        </Navbar.Collapse>
        <Navbar.Collapse className="justify-content-end">
          <Navbar.Text>
            <a href="/logout">Log out</a>
          </Navbar.Text>
        </Navbar.Collapse>
      </Navbar>
    );
  }
}

export default NavbarPage;

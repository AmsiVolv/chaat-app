import React from 'react';
import {Route, Switch } from 'react-router-dom';

import Left from "./Left/Left";
import Right from "./Right/Right";
import Blank from "./Right/Blank";
import NavbarPage from "./Header/NavbarPage";

class App extends React.Component {

    render()
    {
        return (
            <div>
                <NavbarPage/>
                <div className="container py-5 px-4">
                    <div className="row rounded-lg overflow-hidden shadow">
                        <Left/>
                        <Switch >
                            <Route path="/" component={Blank} exact/>
                            <Route path="/conversation/:id"
                                   render={props => <Right {...props} key={props.match.params.id}/>}
                            />
                        </Switch >
                    </div>
                </div>
            </div>
        );
    }
    }

    export default App;
